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
                <input type="url" name="avatar_url" id="avatar_url" class="textbox" placeholder="https://exemple.com/mon_avatar.png" />
                </div>

                <div class="fp-general-infos">
                    
                <input type='text' value='$lang->fiche_personnage_charactername' name='charactername' class='textbox fp-charactername'  placeholder='$lang->fiche_personnage_charactername' />

                <input type='text' value='$lang->fiche_personnage_characterrole' name='role' class='textbox fp-characterrole'  placeholder='$lang->fiche_personnage_characterrole_placeholder' />

                    <div class="fp-petites-infos">
                    <input type='text' value='$lang->fiche_personnage_characterage' name='age' class='textbox fp-characterage'  placeholder='$lang->fiche_personnage_characterage_placeholder' />

                    <input type='text' value='$lang->fiche_personnage_characterbirthdate' name='birthdate' class='textbox fp-birthdate'  placeholder='$lang->fiche_personnage_characterbirthdate_placeholder' />
    
                    <input type='text' value='$lang->fiche_personnage_characterbirthplace' name='birthplace' class='textbox fp-birthplace'  placeholder='$lang->fiche_personnage_characterbirthplace_placeholder' />

                    <input type='text' value='$lang->fiche_personnage_characterlocation' name='location' class='textbox fp-characterlocation'  placeholder='$lang->fiche_personnage_characterlocation_placeholder' />

                    </div>
                </div>

                <div class="fp-summary">
                <label>{$lang->fiche_personnage_charactersummary}</label>
                <textarea name='summary'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
                </textarea>
                </div>

                <div class="fp-personality">
                <label>{$lang->fiche_personnage_characterpersonality}</label>
                <textarea name='personality'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
               </textarea>
                </div>

                <div class="fp-appearance">
                <label>{$lang->fiche_personnage_characterappearance}</label>
                <textarea name='appearance'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

                Pellentesque ut mollis orci. Nulla fermentum tristique turpis, a molestie tortor elementum in. Aliquam eleifend condimentum nisl ut efficitur. Vivamus ac lobortis neque. Praesent viverra ex ac odio hendrerit, vel pulvinar lectus posuere. Curabitur eget lacus diam. Cras vel elit a leo sodales scelerisque. Vestibulum ultricies quam nulla, id convallis lectus cursus eget. Sed tincidunt congue massa et eleifend. Donec quis eros urna.

                Aenean sed ligula ut nisl semper iaculis. Mauris in tellus eleifend, aliquet justo et, dignissim mi. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia curae; Phasellus convallis hendrerit diam sit amet interdum. Suspendisse ac pharetra neque, quis pharetra urna. Praesent vitae ex tristique, finibus quam vitae, rhoncus tellus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nulla convallis quam a velit laoreet, sit amet gravida erat maximus. Morbi ac lacinia libero, sed efficitur ipsum. Vestibulum nisl nisl, facilisis sed finibus vel, vehicula sed libero. Vivamus nec ultrices nunc. Duis pellentesque, purus eget efficitur sagittis, velit leo tristique tellus, sit amet ornare dui lectus id diam. Ut id tortor scelerisque, vulputate purus ac, ullamcorper leo.
               </textarea>

                <label>{$lang->fiche_personnage_characterfashionstyle}</label>
                <textarea name='fashionstyle'></textarea>
                </div>
            
                <div class="fp-story">
                <label>{$lang->fiche_personnage_characterstory}</label>
                <textarea name='story'>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque dui risus, blandit aliquam enim vitae, tristique sagittis arcu. Suspendisse ac efficitur risus. Etiam ultricies sed nunc convallis congue. Suspendisse vel ipsum egestas, lobortis lacus a, imperdiet urna. Donec quis erat nec mauris condimentum tempor quis nec metus. Morbi ornare eros ut fermentum pellentesque. Aliquam suscipit, felis in malesuada tempus, mauris eros aliquam mauris, ac malesuada enim mauris ut lacus. Maecenas vestibulum id mauris eu condimentum.

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
     $role = $db->escape_string($mybb->get_input('role'));
     $age = (int)$mybb->get_input('age');
     $birthdate = $db->escape_string($mybb->get_input('birthdate'));
     $birthplace = $db->escape_string($mybb->get_input('birthplace'));
     $location = $db->escape_string($mybb->get_input('location'));
     $summary = $db->escape_string($mybb->get_input('summary'));
     $personality = $db->escape_string($mybb->get_input('personality'));
     $appearance = $db->escape_string($mybb->get_input('appearance'));
     $fashionstyle = $db->escape_string($mybb->get_input('fashionstyle'));
     $story = $db->escape_string($mybb->get_input('story'));
     $avatar_url = $db->escape_string($mybb->get_input('avatar_url'));

    // Data injection
    $db->insert_query("fp_fiches", [        
    "ficheid" => (int)$fiche_personnage->ficheid,
    "uid" => (int)$mybb->user['uid'],
    "charactername" => $charactername,
    "role" => $role,
    "age" => $age,
    "birthdate" => $birthdate,
    "birthplace" => $birthplace,
    "location" => $location,
    "summary" => $summary,
    "personality" => $personality,
    "appearance" => $appearance,
    "fashionstyle" => $fashionstyle,
    "story" => $story,
    "avatar" => $avatar_url,
    "dateline" => TIME_NOW
    ]);

    // Publication status according to clicked action
    $plublication_status = '';
    if (isset($mybb->input['save'])) {
        $plublication_status = 'incomplete_draft';
        redirect("fichepersonnage.php?ficheid=" . $fiche_personnage->ficheid, $lang->fiche_personnage_saved_draft);
    } elseif (isset($mybb->input['validation'])) {
        $champs = ['charactername', 'role', 'age', 'avatar_url', 'birthdate', 'birthplace', 'location', 'summary', 'personality', 'appearance', 'fashionstyle', 'story'];
        $ok = true;

        foreach ($champs as $champ) {
            if (empty($mybb->input[$champ])) {
                $ok = false;
                break;
            }
        }

        if ($ok) {
            $plublication_status = 'wainting_validation';
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