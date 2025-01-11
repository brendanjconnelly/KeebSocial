<?php echo file_get_contents(getenv("PHP_ROOT") . "/resources/header.php"); ?>

<link rel="stylesheet" href="/settings/settings.css">

<p>Change Bio:</p>
<textarea id="new_bio" placeholder="A KeebSocial User"></textarea>
<br>
<button id="submit_bio" onclick="submit_bio();">Submit</button>
<p id="bio_res"></p>

<br><br>

<p>Change Name:</p>
<input id="new_name" placeholder="New Name"></input>
<br>
<button id="submit_name" onclick="submit_name();">Submit</button>
<p id="name_res"></p>

<p>Change Password:</p>
Old Password: <input id="old_pw" type="password"></input>
<br>
New Password: <input id="new_pw1" type="password"></input>
<br>
Confirm: <input id="new_pw2" type="password"></input>
<br>
<button id="submit_passwd" onclick="submit_passwd();">Submit</button>
<br>
<p id="pw_res"></p>

<script src="/settings/settings.js"></script>
<?php echo file_get_contents(getenv("PHP_ROOT") . "/resources/footer.php"); ?>