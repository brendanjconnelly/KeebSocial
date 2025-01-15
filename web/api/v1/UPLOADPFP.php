<?php

/**
 * UPLOADPFP.php
 * @param user user
 * @param key key
 * @param pfp new pfp (file upload)
 */


if(!isset($_POST["user"]) || !isset($_POST["key"])) exit();
if(!isset($_FILES["pfp"])) {
    exit();
}

require_once getenv("PHP_ROOT") . "/api/helper/USER.php";

$user = $_POST["user"];
$newpath = "/var/keebsocial/assets/pfp/$user.png";
move_uploaded_file($_FILES["pfp"]["tmp_name"], $newpath);
setUserField($user, "pfp", "/assets/pfp/$user.png");
