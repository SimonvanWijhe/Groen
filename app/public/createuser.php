<!-- Default PHP header -->
<?php

require_once('../private/path_constants.php');

$page_title = 'Create user';
$page = "createuser";

require_once(PRIVATE_PATH . '/functions.php');
require_once(PRIVATE_PATH . '/user_functions.php');
require_once(PRIVATE_PATH . '/authorisation_functions.php');
require_once(PRIVATE_PATH . '/User.php');

session_start();
include(SHARED_PATH . '/header.php');

// authorisatiechecks. zie authorisation_functions.php
// de eerste twee functions worden aangeroepen op elke pagina
// de derde wordt aangeroepen op alle pagina's waar alleen admins mogen komen
is_logged_in();
session_expired();
only_for_admins();


?>

<!-- Hier komt de content -->
<div id="content" class="container">

	<div class="table-header-container">
		<h2 class="tabel-header">Voeg gebruiker toe</h2>
	</div>
    <form method="post" action="../private/insert.php">
    <div class="form_container">    
    <div class="form_block form_full_length">

            <label>
                Gebruikersnaam<br>
                <input id="test_username" name="username" type="text" minlength="5" maxlength="45" onkeydown="setTimeout(error_username, 1500)" required/>
            </label>
            <br>
            <p id="error_username" class="error_message"></p>

</div>
<div class="form_block">

            <label>
                Wachtwoord<br>
                <input id="test_password" name="password" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" onkeydown="setTimeout(error_password, 1500)" required/>
            </label>
            <p id="error_pass" class="error_message"></p>

</div>
<div class="form_block">

            <label>
                Herhaal wachtwoord<br>
                <input id="test_password_repeat" name="repeat_password" type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" onkeydown="setTimeout(error_password_repeat, 1500)" required/>
            </label>
            <p id="error_pass_repeat" class="error_message"></p>
</div>
        
        <div class="form_block">

            <label>
                Voornaam<br>
                <input id="test_given_name" name="given_name" type=text minlength="2" maxlenght="45" onkeydown="setTimeout(error_given_name, 1500)" required/>
            </label>
            <p id="error_given_name" class="error_message"></p>
</div>
            <div class="form_block">

            <label>
                Achternaam<br>
                <input id="test_family_name" name="family_name" type=text minlength="2" maxlenght="45" onkeydown="setTimeout(error_family_name, 1500)" required/>
            </label>
            <p id="error_family_name" class="error_message"></p>
</div>
<div class="form_block form_full_length">

            <label>
                Emailadres
                <input id="test_email" name="email" type="email" maxlength="45" onkeydown="setTimeout(error_email, 1500)"  required/>
            </label>
            <p id="error_email" class="error_message"></p>
</div>

<div class="form_block form_full_length">
        <label for="role">Rol</label><br>

        <select name="role" id="role" required>
            <option value="" disabled selected hidden>Kies een rol</option>
            <option value="admin">admin</option>
            <option value="user">user</option>
        </select>
</div>
</div>
<div class="buttons_bottom">
        
            <input type="submit" value="Gebruiker aanmaken" />

            <button onclick="window.location.href = 'userlist.php';"> Annuleren </button>
             </div>
    </form>
</div>

<!-- Nu staat Javascript niet achteraan. Probleem? -->
<script type="text/javascript" src="../private/UserJavascript.js">
</script>

<!-- Default PHP footer -->
<?php include(SHARED_PATH . '/footer.php')?>