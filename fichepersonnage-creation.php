<?php

// Set some useful constants that the core may require or use
define("IN_MYBB", 1);
define('THIS_SCRIPT', 'fichepersonnage-creation.php');

// Including global.php gives us access to a bunch of MyBB functions and variables
require_once "./global.php";

$lang->load('fiche_personnage');

// Verifying if user is connected
if (!$mybb->user['uid']) {
    error_no_permission();
}

$fiche_personnage = new fiche_personnage();

// Testing if the user is allowed to create a new character sheet
if ($fiche_personnage->check_allowed()) {

    // Testing if the user didn't already post a character sheet
    if ($fiche_personnage->never_posted($mybb->user['uid'])) {
        
        // Creating the page with an unique ficheID
        add_breadcrumb($lang->fiche_personnage_creation, "fichepersonnage-creation.php?ficheid=" . $fiche_personnage->ficheid);
         
            // Creating the page with the modele of character sheet
            $fiche_personnage_modele_creation = ' 
           <form class="fp-modele" action="fichepersonnage-creation.php?action=submit" method="post" enctype="multipart/form-data">

            <div class="fp-container">
            
                <div class="fp-avatar">
                <label for="characteravatar_url">$lang->fiche_personnage_characteravatar_url</label>
                <input type="url" name="characteravatar_url" id="characteravatar_url" class="textbox" />
                </div>

                <div class="fp-general-infos">
                <label for="charactername">$lang->fiche_personnage_charactername</label>    
                <input type="text" name="charactername" id="fp-charactername" class="textbox" />

                <label for="characterrole">$lang->fiche_personnage_characterrole</label>  
                <input type="text" name="characterrole" id="fp-characterrole" class="textbox" placeholder="$lang->fiche_personnage_characterrole_placeholder}" />

                    <div class="fp-petites-infos">
                    <label for="characterage">$lang->fiche_personnage_characterage</label>  
                    <input type="text" name="characterage" id="fp-characterage" class="textbox" />

                    <label for="characterbirthdate">$lang->fiche_personnage_characterbirthdate}</label>  
                    <input type="text" name="characterbirthdate" id="fp-birthdate" class="textbox" placeholder="$lang->fiche_personnage_characterbirthdate_placeholder" />
    
                    <label for="characterbirthplace">$lang->fiche_personnage_characterbirthplace</label>  
                    <input type="text" name="characterbirthplace" id="fp-birthplace" class="textbox" placeholder="$lang->fiche_personnage_characterbirthplace_placeholder" />

                    <label for="characterlocation">$lang->fiche_personnage_characterlocation</label>  
                    <input type="text" name="characterlocation" id="fp-characterlocation" class="textbox" placeholder="$lang->fiche_personnage_characterlocatione_placeholder" />

                    </div>
                </div>

                <div class="fp-summary">
                <label for="charactersummary">$lang->fiche_personnage_charactersummary</label>
                <textarea name="charactersummary">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
                </textarea>
                </div>

                <div class="fp-personality">
                <label for="characterpersonality">$lang->fiche_personnage_characterpersonality</label>
                <textarea name="characterpersonality">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
               </textarea>
                </div>

                <div class="fp-appearance">
                <label for="characterappearance">$lang->fiche_personnage_characterappearance</label>
                <textarea name="characterappearance">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
               </textarea>

                <label for="characterfashionstyle">$lang->fiche_personnage_characterfashionstyle</label>
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
                <label for="characterstory">$lang->fiche_personnage_characterstory</label>
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
                    <input type="submit" name="save" value="$lang->fiche_personnage_save_button">
                    <input type="submit" name="validation" value="$lang->fiche_personnage_validation_submit_button">
                </div>
            </form>';

            // Displaying character sheet
            echo $fiche_personnage_modele_creation;  

            // HTML and PHP are banned
            echo strip_tags($fiche_personnage_modele_creation);
 
    } else { //If user already posted : error message
        error($lang->fiche_personnage_already_posted);
        } 
} else { //If user not allowed to create a charecter sheet : error message
    error($lang->fiche_personnage_not_allowed);
    };

// Form
if ($mybb->input['action'] == "submit" && $mybb->request_method == "post") {
    
    // Uploading an avatar
    $avatar_url = $mybb->get_input('avatar_url', MyBB::INPUT_URL);

    // Verifying URL
    if (!filter_var($avatar_url, FILTER_VALIDATE_URL)) 
    {
    error($lang->fiche_personnage_not_correct_avatar_url);
    }

    // Verifying img filetype
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    $extension = pathinfo(parse_url($avatar_url, PHP_URL_PATH), PATHINFO_EXTENSION);

    if (!in_array(strtolower($extension), $allowed_extensions)) {
    error($lang->fiche_personnage_not_correct_avatar_filetype);
    }
    
    // Protecting data injection
     $charactername = $db->escape_string($mybb->get_input('charactername'));
     $characterrole = $db->escape_string($mybb->get_input('characterrole'));
     $characterage =  $db->escape_string($mybb->get_input('characterage'));
     $characterbirthdate = $db->escape_string($mybb->get_input('characterbirthdate'));
     $characterbirthplace = $db->escape_string($mybb->get_input('characterbirthplace'));
     $characterlocation = $db->escape_string($mybb->get_input('characterlocation'));
     $charactersummary = $db->escape_string($mybb->get_input('charactersummary'));
     $characterpersonality = $db->escape_string($mybb->get_input('characterpersonality'));
     $characterappearance = $db->escape_string($mybb->get_input('characterappearance'));
     $characterfashionstyle = $db->escape_string($mybb->get_input('characterfashionstyle'));
     $characterstory = $db->escape_string($mybb->get_input('characterstory'));
     $characteravatar_url = $db->escape_string($mybb->get_input('characteravatar_url'));

    // Data injection
    $db->insert_query("fp_fiches", [        
    "ficheid" => (int)$fiche_personnage->ficheid,
    "uid" => (int)$mybb->user['uid'],
    "charactername" => $charactername,
    "characterrole" => $characterrole,
    "characterage" => $characterage,
    "characterbirthdate" => $characterbirthdate,
    "characterbirthplace" => $characterbirthplace,
    "characterlocation" => $characterlocation,
    "charactersummary" => $charactersummary,
    "characterpersonality" => $characterpersonality,
    "characterappearance" => $characterappearance,
    "characterfashionstyle" => $characterfashionstyle,
    "characterstory" => $characterstory,
    "characteravatar" => $characteravatar_url,
    "characterdateline" => TIME_NOW
    ]);

    // Publication status according to clicked action
    $plublication_status = '';
    if (isset($mybb->input['save'])) {
        $plublication_status = 'incomplete_draft';
        redirect("fichepersonnage.php?ficheid=" . $fiche_personnage->ficheid, $lang->fiche_personnage_saved_draft);
    } elseif (isset($mybb->input['validation'])) {
        $champs = ['charactername', 'characterrole', 'characterage', 'characteravatar_url', 'characterbirthdate', 'characterbirthplace', 'characterlocation', 'charactersummary', 'characterpersonality', 'characterappearance', 'characterfashionstyle', 'characterstory'];
        $ok = true;

        foreach ($champs as $champ) {
            if (empty($mybb->input[$champ])) {
                $ok = false;
                break;
            }
        }

        if ($ok) {
            $plublication_status = 'waiting_validation';
            redirect("fichepersonnage.php?ficheid=" . $fiche_personnage->ficheid, $lang->fiche_personnage_success);
        } else {
            echo "<p>{$lang->fiche_personnage_not_completely_filled}</p>";
            exit;
        }
    }

    // Saving the character sheet in database
    $fiche_personnage->save_fiche($mybb->user['uid'], $mybb->input, $plublication_status);
}

eval("\$page = \"".$templates->get("fichepersonnage_creation")."\";");

output_page($page);

?>
