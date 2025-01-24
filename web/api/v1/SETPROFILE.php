<?php

/**
 * EDITPROFILE.php
 * @param user
 * @param key
 * @param field field to modify
 * @param content new value
 */
require_once getenv("PHP_ROOT") . "/api/helper/RC.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";

$data = json_decode(file_get_contents("php://input"));

if(!cUserExists($data->user)) {
    echo $BAD_ARGUMENT;
    exit();
}
if(!checkToken($data->user, $data->key)) {
    echo $UNAUTHORIZED;
    exit();
}

// Allowed fields to change
if(strcmp($data->field, "name") != 0 && strcmp($data->field, "bio") != 0) {
    echo $BAD_FIELD;
    exit();
}

// Okay
setUserField($data->user, $data->field, $data->content);
echo $SUCCESS;