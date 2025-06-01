<?php 

if(!defined("IN_MYBB")){
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

//All the information about the plugin. These will appear in the plugins list.
function fiche_personnage_info()
{
  global $lang;
	$lang->load('fiche_personnage');

    return array(
        "name"          => $lang->fiche_personnage_name,
        'description'	  => $lang->fiche_personnage_desc,
        "website"       => "http://www.sacremonde.fr",
        "author"        => "Aethera et Moony(Discord)",
        "version"       => "1.0",
        'compatibility' => '18*',
        'codename' => 'fiche_personnage'
    );
}

function fiche_personnage_install()
{
    global $db, $mybb, $lang;

    if (!$db->table_exists('fp_ficheid')) {
        $db->write_query("CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "fp_ficheid` (
          ".fichepersonnage_generate_table_fields("fp_ficheid")."
          PRIMARY KEY (`ficheid`,`uid`,`charactername`,`age`,`birthdate`,`birthplace`,`role`,`location`,`summary`,`personality`,`appearance`,`fashionstyle`,`story`,`avatar_url`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ");
    }
    
    if (!$db->table_exists('fp_family')) {
        $db->write_query("CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "fp_family` (
          ".fichepersonnage_generate_table_fields("fp_family")."
          PRIMARY KEY (`id`,`ficheid`,`name`,`relation`,`age`,`description`,`location`,`role`,`playablestatus`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ");
    }
    
    if (!$db->table_exists('fp_dragon')) {
        $db->write_query("CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "fp_dragon` (
          ".fichepersonnage_generate_table_fields("fp_dragon")."
          PRIMARY KEY (`id`,`ficheid`,`dragonname`,`color`,`clutchdate`,`parents`,`appearance`,`personality`,`image`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ");
    }

    if (!$db->table_exists('fp_magic')) {
        $db->write_query("CREATE TABLE IF NOT EXISTS `" . TABLE_PREFIX . "fp_magic` (
          ".fichepersonnage_generate_table_fields("fp_magic")."
          PRIMARY KEY (`id`,`ficheid`,`magictype`,`description`,`guilde`,`rank`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
        ");
    }

// Create settings.
    $setting_group = array(
      'name' => 'fiche_personnage_settings',
      'title' => $lang->fiche_personnage_settings_title,
      'description' => $lang->fiche_personnage_settings_desc,
      'disporder' => 5, // The order your setting group will display
      'isdefault' => 0
  );
  
  $gid = $db->insert_query("settinggroups", $setting_group);
  
  $setting_array = array(
      'fiche_personnage_forum_publication' => array(
        'title' => $lang->fiche_personnage_publication_forum_title,
        'description' => $lang->fiche_personnage_publication_forum_desc,
        'optionscode' => 'forumselectsingle',
        'disporder' => 1
    ),
      'fiche_personnage_button_publication' => array(
        'title' => $lang->fiche_personnage_publication_button_title,
        'description' => $lang->fiche_personnage_publication_button_desc,
        'optionscode' => 'yesno',
        'value' => 1,
        'disporder' => 2
    ),
    'fiche_personnage_usergroups' => array(
			'title' => $lang->fiche_personnage_usergroups_title,
			'description' => $lang->fiche_personnage_usergroups_desc,
			'optionscode' => 'groupselect',
			'disporder' => 3
		)
  );
  
  foreach($setting_array as $name => $setting)
  {
    $setting['name'] = $name;
    $setting['gid'] = $gid;
  
    $db->insert_query('settings', $setting);
  }
  
  // Don't forget this!
  rebuild_settings();

  // Add stylesheet
     include MYBB_ROOT."/admin/inc/functions_themes.php";
     $db->insert_query("themestylesheets",array(
       'name' => 'fichepersonnage-style.css',
       'tid' => 1,
       'attachedto' => "fichepersonnage-creation.php",
       'cachefile' => "fichepersonnage-style.css",
       'lastmodified' => time()
     ));
     update_theme_stylesheet_list(1);
}

function fiche_personnage_is_installed()
{
  global $db;
    
  // If the table exists then it means the plugin is installed because we only drop it on uninstallation
  return $db->table_exists('fp_ficheid') && $db->table_exists('fp_family');
}

function fiche_personnage_uninstall()
{
  global $db, $mybb;

  // Drop tables if desired
  if (!isset($mybb->input['no'])) {
      $db->drop_table('fp_ficheid');
      $db->drop_table('fp_family');
      $db->drop_table('fp_dragon');
      $db->drop_table('fp_magic');
  }

  // Delete settings of the plugin
    $db->delete_query('settings', "name IN ('fiche_personnage_forum_publication','fiche_personnage_button_publication','fiche_personnage_usergroups')");
    $db->delete_query('settinggroups', "name = 'fiche_personnage_settings'");

  // Don't forget this
    rebuild_settings();
  
  // Delete stylesheets
    include MYBB_ROOT."/admin/inc/functions_themes.php";
    $db->delete_query("themestylesheets",array(
      'name' => 'fichepersonnage-style.css',
      'tid' => 1,
      'attachedto' => "fichepersonnage-creation.php",
      'cachefile' => "fichepersonnage-style.css",
      'lastmodified' => time()
    ));
    update_theme_stylesheet_list(1);
}

//All the activation processes go here
function fiche_personnage_activate()
{
  global $db


}

//All deactivation processes go here
function fiche_personnage_deactivate()
{

}

?>