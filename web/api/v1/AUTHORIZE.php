<?php
require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";

// Used for dynamic authentication (i.e. this has to be included by a PHP file that serves content)
// THIS FILE CANNOT BE USED FOR PURE BACKEND TOKEN AUTHENTICATION

$args = json_decode(file_get_contents('php://input'));
if(!(isset($args->username) || !isset($args->token)) || $args->username === "" || $args->token === "") {
    echo '10';
    exit();
}

$username = $args->username;
$token = $args->token;

echo checkToken($username, $token) ? '0' : '1';