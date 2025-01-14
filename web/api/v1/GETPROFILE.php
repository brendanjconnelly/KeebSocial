<?php

/**
 * GETPROFILE.php
 * 
 * @param user who is it?
 * @param key what's the password?
 * @param target who are you talking about?
 * @param field yeah what about him?
 * what am i doing with my life
 */

require_once getenv("PHP_ROOT") . "/api/helper/USER.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";
require_once getenv("PHP_ROOT") . "/api/helper/DB.php";

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->key) || !isset($data->target) || !isset($data->user) || !isset($data->field)) {
    echo '10';
    exit();
}

if(!_isValidUserField($data->field)) {
    echo '10';
    exit();
}

echo getUserField($data->target, $data->field);