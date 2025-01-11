<?php

/**
 * PASSWD.php
 * @param user
 * @param key
 * @param old
 * @param new
 */

require_once getenv("PHP_ROOT") . "/api/helper/USER.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";

$data = json_decode(file_get_contents("php://input"));

if(!cUserExists($data->user)) {
    echo '20';
    exit();
}
if(!checkToken($data->user, $data->key)) {
    echo '30';
    exit();
}

if(!authenticate($data->user, $data->old)) {
    echo '10';
    exit();
}

$password = $data->new;
global $keebsocial_users;

$keebsocial_users->users->updateOne(
    [
        'username' => $data->user
    ],
    [
        '$set' => [
            'hash' => _ksHash($password)
        ]
    ]
);

echo '0';