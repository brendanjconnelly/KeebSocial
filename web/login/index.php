<?php echo file_get_contents(getenv("PHP_ROOT") . "/resources/header.php"); ?>
<p>no emails yet :(</p>
<br><br>

<?php
// Check if already logged in
require_once getenv("PHP_ROOT") . "/api/helper/CHECK.php";
if($check_authenticated) {
    header("Location: /");
    die();
}

?>
<h3>Login</h3>

<p><input type="text" id="username" placeholder="Username"></input></p>
<p><input type="password" id="password" placeholder="Password"></input></p>
<button id="login_button" onclick="login();">Login</button>
<p id="login_result"></p>

<h3>Register</h3>
<p><input type="text" id="newfullname" placeholder="Full Name"></input></p>
<p><input type="text" id="newusername" placeholder="Username"></input></p>
<p>Your username must be all lowercase.</p>
<p><input type="password" id="newpassword" placeholder="Password"></input></p>
<button id="register_button" onclick="register();">Register</button>
<p id="register_result"></p>
<br>
<p>By logging in or registering, you agree to store KeebSocial cookies on your computer.
    We use these cookies to authenticate you so nobody else can access your KeebSocial account.</p>
<?php echo file_get_contents(getenv("PHP_ROOT") . "/resources/footer.php"); ?>