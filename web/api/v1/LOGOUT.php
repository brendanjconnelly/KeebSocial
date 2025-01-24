<?php

/**
 * LOGOUT.php
 * Used to clear an authentication token. Not strictly required to log out, but probably a good idea.
 */
require_once getenv("PHP_ROOT") . "/api/helper/RC.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";
$data = json_decode(file_get_contents("php://input"));

if(isset($data->username) && isset($data->token)) {
    deleteToken($data->username, $data->token);
    echo $SUCCESS;
    exit();
}
echo $BAD_PARAMS;

?>