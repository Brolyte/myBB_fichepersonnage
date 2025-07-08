<?php 

if(!defined("IN_MYBB")){
    die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

// All the information about the plugin. These will appear in the plugins list.

function fiche_personnage_info()
{
  global $lang;
	$lang->load('config_fiche_personnage');

    return array(
        'name'          => $lang->fiche_personnage_name,
        'description'	  => $lang->fiche_personnage_desc,
        "website"       => "http://www.sacremonde.fr",
        "author"        => "Aethera",
        "version"       => "1.0",
        'compatibility' => '18*',
        'codename' => 'fiche_personnage'
    );
    // Thank you for your help Moony(Discord)!
}


function fiche_personnage_install()
{
    global $db;

if (!$db->table_exists('fp_fiche')) {
        $db->write_query("
				CREATE TABLE " . TABLE_PREFIX . "fp_fiche(                
					`ficheid` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`uid` int(11) unsigned NOT NULL,
					`fid` int(11) unsigned NOT NULL,
                    `tid` int(11) unsigned DEFAULT NULL,
					`fichestatus` enum('pending', 'approved', 'draft', 'archived') DEFAULT 'pending',
					`fichesubject` varchar(512) NOT NULL DEFAULT '',
					`datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`charactername` varchar(255) NOT NULL DEFAULT '',
					`characterage` varchar(255) NOT NULL DEFAULT '',
					`characterbirthdate` varchar(255) NOT NULL DEFAULT '',
					`characterbirthplace` varchar(255) NOT NULL DEFAULT '',
					`characterrole` varchar(255) NOT NULL DEFAULT '',
					`characterlocation` varchar(255) NOT NULL DEFAULT '',
					`charactersummary` varchar(512) NOT NULL DEFAULT '',
					`characterpersonality` text NOT NULL,
					`characterappearance` text NOT NULL,
					`characterfashionstyle` text NOT NULL,
					`characterstory` text NOT NULL,
					`characteravatar_url` text NOT NULL,
					PRIMARY KEY (`ficheid`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			");
    }

    if (!$db->table_exists('fp_family')) {
        $db->write_query("
				CREATE TABLE " . TABLE_PREFIX . "fp_family(                
					`ficheid` int(11) unsigned NOT NULL,
					`familymemberid` int(11) unsigned NOT NULL AUTO_INCREMENT,
					`familymembername` varchar(255) NOT NULL DEFAULT '',
					`familymemberrelation` varchar(255) NOT NULL DEFAULT '',
					`familymemberage` varchar(255) NOT NULL DEFAULT '',
					`familymemberdescription` varchar(512) NOT NULL DEFAULT '',
					`familymemberlocation` varchar(255) NOT NULL DEFAULT '',
					`familymemberrole` varchar(512) NOT NULL DEFAULT '',
					`familymemberplayablestatus` varchar(255) NOT NULL DEFAULT '',
					PRIMARY KEY (`familymemberid`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			");
    }

        if (!$db->table_exists('fp_dragon')) {
        $db->write_query("
				CREATE TABLE " . TABLE_PREFIX . "fp_dragon(                
					`ficheid` int(11) unsigned NOT NULL,
					`dragonname` varchar(255) NOT NULL DEFAULT '',
					`dragoncolor` varchar(255) NOT NULL DEFAULT '',
					`dragonclutchdate` varchar(255) NOT NULL DEFAULT '',
					`dragonparents` varchar(512) NOT NULL DEFAULT '',
					`dragonappearance` text NOT NULL,
					`dragonpersonality` text NOT NULL,
					`dragonimage` varchar(512) NOT NULL DEFAULT '',
                    PRIMARY KEY (dragonname)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			");
    }
  
        if (!$db->table_exists('fp_magic')) {
        $db->write_query("
				CREATE TABLE " . TABLE_PREFIX . "fp_magic(                
					`ficheid` int(11) unsigned NOT NULL,               
					`uid` int(11) unsigned NOT NULL,
					`magicname` varchar(512) NOT NULL DEFAULT '',
					`magictype` varchar(512) NOT NULL DEFAULT '',
					`magicdescription` varchar(512) NOT NULL DEFAULT '',
					`magicguilde` varchar(255) NOT NULL DEFAULT '',
					`magicrank` varchar(255) NOT NULL DEFAULT ''                  
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;
			");
    }
}

function fiche_personnage_is_installed()
{
	global $db;
    
	// If the table exists then it means the plugin is installed because we only drop it on uninstallation
	if($db->table_exists("fp_fiche") && $db->table_exists("fp_family") && $db->table_exists("fp_dragon") && $db->table_exists("fp_magic"))
    {
        return true;
    }
    return false;
}

function fiche_personnage_uninstall()
{
  global $db, $mybb;

    // Drop tables if desired
    if (!isset($mybb->input['no'])) {
      $db->drop_table('fp_fiche');
      $db->drop_table('fp_family');
      $db->drop_table('fp_dragon');
      $db->drop_table('fp_magic');
    }

	// Delete template groups.
    $db->delete_query('templategroups', "prefix='fiche_personnage'");

    // Delete templates belonging to template groups.
    $db->delete_query('templates', "title='Fiche de personnage' OR title LIKE 'fiche_personnage_%'");
}

// Create the templates used by the plugin
function fiche_personnage_get_templates(){
    $templatearray = array(
        'creation_form' => '
			<form class="fp-modele" action="fichepersonnage-creation.php?action=submit" method="post" enctype="multipart/form-data">

            <div class="fp-container">
            
                <div class="fp-avatar">
                <label for="characteravatar_url">{$lang->fiche_personnage_characteravatar_url}</label>
                <input type="url" name="characteravatar_url" id="characteravatar_url" class="textbox" />
                </div>

                <div class="fp-general-infos">
                <label for="charactername">{$lang->fiche_personnage_charactername}</label>    
                <input type="text" name="charactername" id="fp-charactername" class="textbox" />

                <label for="characterrole">{$lang->fiche_personnage_characterrole}</label>  
                <input type="text" name="characterrole" id="fp-characterrole" class="textbox" placeholder="{$lang->fiche_personnage_characterrole_placeholder}" />

                    <div class="fp-petites-infos">
                    <label for="characterage">{$lang->fiche_personnage_characterage}</label>  
                    <input type="text" name="characterage" id="fp-characterage" class="textbox" />

                    <label for="characterbirthdate">{$lang->fiche_personnage_characterbirthdate}</label>  
                    <input type="text" name="characterbirthdate" id="fp-birthdate" class="textbox" placeholder="{$lang->fiche_personnage_characterbirthdate_placeholder}" />
    
                    <label for="characterbirthplace">{$lang->fiche_personnage_characterbirthplace}</label>  
                    <input type="text" name="characterbirthplace" id="fp-birthplace" class="textbox" placeholder="{$lang->fiche_personnage_characterbirthplace_placeholder}" />

                    <label for="characterlocation">{$lang->fiche_personnage_characterlocation}</label>  
                    <input type="text" name="characterlocation" id="fp-characterlocation" class="textbox" placeholder="{$lang->fiche_personnage_characterlocatione_placeholder}" />

                    </div>
                </div>

                <div class="fp-summary">
                <label for="charactersummary">{$lang->fiche_personnage_charactersummary}</label>
                <textarea name="charactersummary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
                </textarea>
                </div>

                <div class="fp-personality">
                <label for="characterpersonality">{$lang->fiche_personnage_characterpersonality}</label>
                <textarea name="characterpersonality">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
               </textarea>
                </div>

                <div class="fp-appearance">
                <label for="characterappearance">{$lang->fiche_personnage_characterappearance}</label>
                <textarea name="characterappearance">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
               </textarea>

                <label for="characterfashionstyle">{$lang->fiche_personnage_characterfashionstyle}</label>
                <textarea name="characterfashionstyle">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
               
               Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.               
                </textarea>
                </div>
            
                <div class="fp-story">
                <label for="characterstory">{$lang->fiche_personnage_characterstory}</label>
                <textarea name="characterstory">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
               
               Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
                </textarea>
                </div>

            </div>

                <div class="fp-btn-group">
                    <input type="submit" name="save" value="{$lang->fiche_personnage_save_button}">
                    <input type="submit" name="validation" value="{$lang->fiche_personnage_validation_submit_button}">
                </div>
            </form>',
    	'visionnage' => '
			<div class="fp-container">
            
                <div class="fp-avatar">
                <label for="characteravatar_url">{$lang->fiche_personnage_characteravatar_url}</label>
                <input type="url" name="characteravatar_url" id="characteravatar_url" class="textbox" />
                </div>

                <div class="fp-general-infos">
                <label for="charactername">{$lang->fiche_personnage_charactername}</label>    
                <input type="text" name="charactername" id="fp-charactername" class="textbox" />

                <label for="characterrole">{$lang->fiche_personnage_characterrole}</label>  
                <input type="text" name="characterrole" id="fp-characterrole" class="textbox" placeholder="{$lang->fiche_personnage_characterrole_placeholder}" />

                    <div class="fp-petites-infos">
                    <label for="characterage">{$lang->fiche_personnage_characterage}</label>  
                    <input type="text" name="characterage" id="fp-characterage" class="textbox" />

                    <label for="characterbirthdate">{$lang->fiche_personnage_characterbirthdate}</label>  
                    <input type="text" name="characterbirthdate" id="fp-birthdate" class="textbox" placeholder="{$lang->fiche_personnage_characterbirthdate_placeholder}" />
    
                    <label for="characterbirthplace">{$lang->fiche_personnage_characterbirthplace}</label>  
                    <input type="text" name="characterbirthplace" id="fp-birthplace" class="textbox" placeholder="{$lang->fiche_personnage_characterbirthplace_placeholder}" />

                    <label for="characterlocation">{$lang->fiche_personnage_characterlocation}</label>  
                    <input type="text" name="characterlocation" id="fp-characterlocation" class="textbox" placeholder="{$lang->fiche_personnage_characterlocatione_placeholder}" />

                    </div>
                </div>

                <div class="fp-summary">
                <label for="charactersummary">{$lang->fiche_personnage_charactersummary}</label>
                <textarea name="charactersummary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
                </textarea>
                </div>

                <div class="fp-personality">
                <label for="characterpersonality">{$lang->fiche_personnage_characterpersonality}</label>
                <textarea name="characterpersonality">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
               </textarea>
                </div>

                <div class="fp-appearance">
                <label for="characterappearance">{$lang->fiche_personnage_characterappearance}</label>
                <textarea name="characterappearance">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
               </textarea>

                <label for="characterfashionstyle">{$lang->fiche_personnage_characterfashionstyle}</label>
                <textarea name="characterfashionstyle">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
               
               Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.               
                </textarea>
                </div>
            
                <div class="fp-story">
                <label for="characterstory">{$lang->fiche_personnage_characterstory}</label>
                <textarea name="characterstory">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
               
               Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
                </textarea>
                </div>

            </div>
		');

    return $templatearray;
}


//All the activation processes go here
function fiche_personnage_activate()
{
	global $db, $mybb, $lang;
    
	$group = [
        'prefix' => 'fp',
        'title' => 'Fiche de personnage',
        'isdefault' => 0
    ];
    $db->insert_query('templategroups', $group);

    $templates = fiche_personnage_get_templates();

    foreach ($templates as $name => $template) {
        $insert_array = [
            'title' => 'fp_' . $name,
            'template' => $db->escape_string($template),
            'sid' => -2,
            'version' => '',
            'dateline' => TIME_NOW
        ];
        $db->insert_query('templates', $insert_array);
    }
	
	// Create settings group in ACP.
    $insertarray = array(
      'name' => 'fiche_personnage_settings',
      'title' => $lang->fiche_personnage_settings_title,
      'description' => $lang->fiche_personnage_settings_desc,
      'disporder' => 1, // The order your setting group will display
      'isdefault' => 0
    );
	$gid = $db->insert_query("settinggroups", $insertarray);

	
    // Declare the settings
    // Setting the group(s) allowed to moderate the awaiting characters
    $setting = array(
		'sid'			=> NULL,
		'name'			=> 'fiche_personnage_allowtomoderate',
		'title'			=> $lang->fiche_personnage_allowtomoderate_title,
		'description'	=> $lang->fiche_personnage_allowtomoderate_desc,
		'optionscode'	=> 'groupselect',
		'value'			=> '',
		'disporder'		=> 1,
		'gid'			=> $gid
	);

	$db->insert_query('settings', $setting);
    
    // Setting the group(s) allowed to create a character sheet
    $setting = array(
		'sid'			=> NULL,
		'name'			=> 'fiche_personnage_allowtopost',
		'title'			=> $lang->fiche_personnage_allowtopost_title,
		'description'	=> $lang->fiche_personnage_allowtopost_desc,
		'optionscode'	=> 'groupselect',
		'value'			=> '',
		'disporder'		=> 2,
		'gid'			=> $gid
	);

	$db->insert_query('settings', $setting);
    
    // Setting the number of character sheets allowed per user
    $setting = array(
		'sid'			=> NULL,
		'name'			=> 'fiche_personnage_postlimit',
		'title'			=> $lang->fiche_personnage_postlimit_title,
		'description'	=> $lang->fiche_personnage_postlimit_desc,
		'optionscode'	=> 'numeric',
		'value'			=> '0', // 0 means unlimited
		'disporder'		=> 3,
		'gid'			=> $gid
	);

	$db->insert_query('settings', $setting);

    // Setting the forum where the character sheets waiting for validation will be published
	$setting = array(
		'sid'			=> NULL,
		'name'			=> 'fiche_personnage_awaitinglist_forum',
		'title'			=> $lang->fiche_personnage_awaitinglist_forum_title,
		'description'	=> $lang->fiche_personnage_awaitinglist_forum_desc,
		'optionscode'	=> 'forumselectsingle',
		'value'			=> '',
		'disporder'		=> 4,
		'gid'			=> $gid
	);

	$db->insert_query('settings', $setting);

    // Setting the forum where the approved character sheets will be published
	$setting = array(
		'sid'			=> NULL,
		'name'			=> 'fiche_personnage_approvedlist_forum',
		'title'			=> $lang->fiche_personnage_approvedlist_forum_title,
		'description'	=> $lang->fiche_personnage_approvedlist_forum_desc,
		'optionscode'	=> 'forumselectsingle',
		'value'			=> '',
		'disporder'		=> 5,
		'gid'			=> $gid
	);

	$db->insert_query('settings', $setting);

    // Setting the text of the button to create a character sheet instead of the regular "New Thread" button  
	$setting = array(
		'sid'			=> NULL,
		'name'			=> 'fiche_personnage_button_custom_text',
		'title'			=> $lang->fiche_personnage_button_custom_text_title,
		'description'	=> $lang->fiche_personnage_button_custom_text_desc,
		'optionscode'	=> 'text',
		'value'			=> 'Nouveau personnage',
		'disporder'		=> 6,
		'gid'			=> $gid
	);

	$db->insert_query('settings', $setting);

    // Setting the style of the button to create a character sheet instead of the regular "New Thread" button
	$setting_array = array(
    'name' => 'fiche_personnage_button_custom_css',
    'title' => $lang->fiche_personnage_button_custom_css_title,
    'description' => $lang->fiche_personnage_button_custom_css_desc,
    'optionscode' => 'textarea',
    'value' => '',
    'disporder' => 7,
    'gid' => $gid
    );

    $db->insert_query('settings', $setting_array);

    // Setting the text of the prefix for the character sheets
	$setting = array(
		'sid'			=> NULL,
		'name'			=> 'fiche_personnage_prefix',
		'title'			=> $lang->fiche_personnage_prefix_title,
		'description'	=> $lang->fiche_personnage_prefix_desc,
		'optionscode'	=> 'text',
		'value'			=> 'Fiche',
		'disporder'		=> 8,
		'gid'			=> $gid
	);

	$db->insert_query('settings', $setting);

    // Setting an icon for the character sheets
	$setting = array(
		'sid'			=> NULL,
		'name'			=> 'fiche_personnage_icon',
		'title'			=> $lang->fiche_personnage_icon_title,
		'description'	=> $lang->fiche_personnage_icon_desc,
		'optionscode'	=> 'text',
		'value'			=> '/dossier/fiche_personnage_icon.png',
		'disporder'		=> 9,
		'gid'			=> $gid
	);

	$db->insert_query('settings', $setting);

    // Adding or not a link to the character sheet in the user profile
	$setting = array(
		'sid'			=> NULL,
		'name'			=> 'fiche_personnage_linktoprofile',
		'title'			=> $lang->fiche_personnage_linkinprofile_title,
		'description'	=> $lang->fiche_personnage_linkinprofile_desc,
		'optionscode'	=> 'yesno',
		'value'			=> '1',
		'disporder'		=> 10,
		'gid'			=> $gid
	);

	$db->insert_query('settings', $setting);

    // Adding or not a link to the character sheet in the member list
	$setting = array(
		'sid'			=> NULL,
		'name'			=> 'fiche_personnage_linkinmemberlist',
		'title'			=> $lang->fiche_personnage_linkinmemberlist_title,
		'description'	=> $lang->fiche_personnage_linkinmemberlist_desc,
		'optionscode'	=> 'yesno',
		'value'			=> '1',
		'disporder'		=> 11,
		'gid'			=> $gid
	);

	$db->insert_query('settings', $setting);

	// Don't forget this!
	rebuild_settings();

    // Add stylesheet
    $tid = 1; // MyBB Master Style
    $name = "fiche_personnage_style.css";
    $styles = file_get_contents(MYBB_ROOT . 'inc/plugins/fiche_personnage/fiche_personnage_style.css');
    $attachedto = "fiche_personnage_creation.php, fiche_personnage_edition.php";

    $stylesheet = array(
        'name' => $name,
        'tid' => $tid,
        'attachedto' => $attachedto,
        'stylesheet' => $styles,
        'cachefile' => $name,
        'lastmodified' => TIME_NOW,
        );

    $dbstylesheet = array_map(array($db, 'escape_string'), $stylesheet);

    // Activate children, if present.
    $db->update_query('themestylesheets', array('attachedto' => $dbstylesheet['attachedto']), "name='{$dbstylesheet['name']}'");

    // Update or insert parent stylesheet.
    $query = $db->simple_select('themestylesheets', 'sid', "tid='{$tid}' AND cachefile='{$name}'");
    $sid = intval($db->fetch_field($query, 'sid'));

    if ($sid) {
        $db->update_query('themestylesheets', $dbstylesheet, "sid='$sid'");
    } else {
        $sid = $db->insert_query('themestylesheets', $dbstylesheet);
        $stylesheet['sid'] = intval($sid);
    }

    require_once MYBB_ROOT . $mybb->config['admin_dir'] . '/inc/functions_themes.php';

    if ($stylesheet) {
        cache_stylesheet($stylesheet['tid'], $stylesheet['cachefile'], $stylesheet['stylesheet']);
    }

    update_theme_stylesheet_list($tid, false, true); // includes all children
}

//All deactivation processes go here
function fiche_personnage_deactivate()
{
	global $db;
	// Delete templates
    $db->delete_query('templates', "title LIKE 'fiche_personnage_%'");

    // Delete templates group
    $db->delete_query('templategroups', "prefix = 'fiche_personnage'");

	// Delete settings group
	$db->delete_query("settinggroups", "name = 'fiche_personnage_settings'");

	// Remove settings
	$db->delete_query('settings', 'name IN (\'fiche_personnage_allowtomoderate\',\'fiche_personnage_allowtopost\',\'fiche_personnage_postlimit\',\'fiche_personnage_awaitinglist_forum\',\'fiche_personnage_approvedlist_forum\',\'fiche_personnage_button_custom_text\',\'fiche_personnage_button_custom_css\',\'fiche_personnage_prefix\',\'fiche_personnage_icon\',\'fiche_personnage_linkinprofile\', \'fiche_personnage_linkinmemberlist\')');

	// Don't forget this!
	rebuild_settings();
}

// Identifying usergroups allowed to moderate the character sheets and unlock moderation actions 
function fiche_personnage_allowtomoderate_usergroups()
{

}

// Replacing the link (and text) of the "New Thread" button 
$plugins->add_hook("forumdisplay_get_threads", "fiche_personnage_awaitingforum_get_threads");
function fiche_personnage_awaitingforum_get_threads()
{
    global $db, $foruminfo, $fpermissions, $mybb, $newthread;

    $awaitinglist_forum_id = (int)$mybb->settings['fiche_personnage_awaitinglist_forum'];    
    $button_custom_text = htmlspecialchars_uni($mybb->settings['fiche_personnage_fichecreation_button']);
    $button_custom_css = trim($mybb->settings['fiche_personnage_button_custom_css']);

    // Verifying if we are in the forum where character sheets are created and remplacing the "New Thread" button with a custom button
    if ($foruminfo['type'] == "f" && $foruminfo['fid'] == $awaitinglist_forum_id && $foruminfo['open'] != 0 && $fpermissions['canpostthreads'] != 0 && $mybb->user['suspendposting'] == 0) 
        {   
            if (!empty($button_custom_css)) {
                $newthread = "<a href='nouvelle_fiche_personnage.php' style=\"{$button_custom_css}\"><span>{$button_custom_text}</span></a>";
            } else {
                $newthread = "<a href='nouvelle_fiche_personnage.php' class='button new_thread_button'><span>{$button_custom_text}</span></a>";
            } 
        }
}

// Verifying if the forum where character sheets are created is the same as the forum where they are approved, and if not delete the "New Thread" button of the display forum 
$plugins->add_hook("forumdisplay_get_threads", "fiche_personnage_forumdisplay_delete_newthread");
function fiche_personnage_forumdisplay_delete_newthread()
{
global $foruminfo, $mybb, $newthread;

$awaiting_forum_id = (int)$mybb->settings['fiche_personnage_awaitinglist_forum'];
$approvedlist_forum_id = (int)$mybb->settings['fiche_personnage_approvedlist_forum'];

if ($foruminfo['fid'] == $approvedlist_forum_id) {

    if ($approvedlist_forum_id === $awaiting_forum_id or $approvedlist_forum_id === -1) {

        fiche_personnage_awaitingforum_get_threads();

    } else {

        unset($newthread);
    }
} 
}

// Verifying if the user reached the limit of character sheets
function fiche_personnage_has_posted($uid)
{
    global $mybb, $db;

    $uid = (int)$uid;
    if ($uid <= 0) {
        return false;
    }

    $fp_limit = (int)$mybb->settings['fiche_personnage_postlimit'];

    $query = $db->simple_select("fp_fiche", "COUNT(*) as total", "uid='{$uid}'");
    $fp_total = (int)$db->fetch_field($query, "total");

    if($fp_total <= $fp_limit && $fp_limit > 0) {
		return true; // User has not reached the limit
	} elseif ($fp_limit <= 0) {
		return true; // Unlimited, so always true
	} else {
		return false; // User has reached the limit
	}
}

$plugins->add_hook("forumdisplay_get_threads", "fiche_personnage_alreadypublished_delete_newthread");
function fiche_personnage_alreadypublished_delete_newthread()
{
    global $foruminfo, $mybb, $newthread;
  
    $uid = (int)$mybb->user['uid'];
    $pending_forum_id = (int)$mybb->settings['fiche_personnage_awaitinglist_forum'];

    if ($foruminfo['fid'] == $pending_forum_id && fiche_personnage_has_posted($uid)) {
        unset($newthread); // Already posted a character sheet, so we remove the "New Thread" button
    }
}


// Adding a prefix to the character sheet thread title
$plugins->add_hook("forumdisplay_thread", "fiche_personnage_remplace_threadsubject");
function fiche_personnage_remplace_threadsubject()
{
    global $mybb;

    $fiche_prefix = htmlspecialchars_uni($mybb->settings['fiche_personnage_prefix']);

    if ($fiche_prefix !== "") {
    
    $fiche_prefix;  
    }
}

// Adding a link to the character sheet in the memberlist
$plugins->add_hook("memberlist_user", "fiche_personnage_memberlist_link");
function fiche_personnage_memberlist_link(&$user)
{
    global $db, $mybb, $lang;
         
    $uid = (int)$user['uid'];
    
    $query = $db->simple_select("fp_fiche", "ficheid", "uid=" . (int)$uid, ["order_by" => "datetime", "order_dir" => "DESC", "limit" => 1]);
    $fiche_id = $db->fetch_array($query);

    $query = $db->simple_select("fp_fiche", "fichestatus", "uid=" . (int)$uid, ["order_by" => "datetime", "order_dir" => "DESC", "limit" => 1]);
    $fiche_validated = $db->fetch_array($query);
   
    $link_in_memberlist = (int)$mybb->settings['fiche_personnage_linkinmemberlist'];

    // Creating a column in the memberlist to display the character sheet link if option is enabled    
    if ($link_in_memberlist === 1 && $fiche_validated['fichestatus'] === 'Validée') {
        $ficheid = (int)$fiche['ficheid'];

    require_once MYBB_ROOT . '/inc/adminfunctions_templates.php';

    // Adding the title of the column in the memberlist template
    find_replace_templatesets(
    'memberlist_user',
    '#{$user[\'profilelink\']}<br />#i',
    '{$user[\'profilelink\']} <a href="fiche-personnage.php?ficheid=' . $ficheid . '" class="fplink_in_memberlist" title="Voir la fiche">({$lang->fiche_personnage_memberlist_linktofp})</a><br />'
    );

    } else {
    return;
    }

}

?>
