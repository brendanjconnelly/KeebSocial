<?php
/**
 * FOLLOWQUERY.php
 * Used to check if a user follows a target
 * @param user invoking user
 * @param key auth token
 * @param target user to check
 * @return 0 on success idk what the rest is bro
 */
require_once getenv("PHP_ROOT") . "/api/helper/RC.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";
require_once getenv("PHP_ROOT") . "/api/helper/DB.php";

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->key) || !isset($data->target) || !isset($data->user)) {
    echo $BAD_PARAMS;
    exit();
}
if(!cUserExists($data->target) || !cUserExists($data->user)) {
    echo $BAD_ARGUMENT;
    exit();
}
if(!checkToken($data->user, $data->key)) {
    echo $UNAUTHORIZED;
    exit();
}

echo doesUserFollow($data->user, $data->target) ? '1' : '0'; // 1 if the user follows target, 0 if not

?>