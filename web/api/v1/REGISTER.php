<?php

/**
 * REGISTER.PHP
 * Used to register a new user in the password and in the accounts database
 * KI0001.1
 * @param user Username to register new account under
 * @param name name
 * @param password Password to associate with new username
 * @return 0|1
 */
require_once getenv("PHP_ROOT") . "/api/helper/RC.php";

require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER.php";

$data = json_decode(file_get_contents("php://input"));

// bad params
if(!isset($data->username) || !isset($data->name) || !isset($data->password)) {
    echo $BAD_PARAMS;
    exit();
}

if(userExists($data->username)) {
    echo $BAD_ARGUMENT;
    exit();
}

// create acc

createUser($data->username, "", $data->password); // registers user in the auth database (w/ blank email as argv[1])
initializeUser($data->username, $data->name); // initializes user profile in the content database
echo $SUCCESS;