<?php

/**
 * LOGIN.php
 * KI0001.1
 * @param username
 * @param password
 * @return 0|1|token
 * @return 0 on wrong password
 * @return 1 on user does not exist
 * @return token on valid login 
 */

require_once getenv("PHP_ROOT") . "/api/helper/RC.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";
$data = json_decode(file_get_contents('php://input'));

if(!userExists($data->username)) {
    echo $BAD_ARGUMENT;
    die();
}

echo getNewToken($data->username, $data->password);