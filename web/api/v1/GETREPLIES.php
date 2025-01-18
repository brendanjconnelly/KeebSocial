<?php

/**
 * GETREPLIES.php
 * @param user user
 * @param key token
 * @param parent
 * @return json!!!
 */


require_once getenv("PHP_ROOT") . "/api/helper/POST.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";
$data = json_decode(file_get_contents('php://input'));

if(!isset($data->user) || !isset($data->key) || !isset($data->parent)) {
    exit();
}

if(!cUserExists($data->user)) {
    echo '20';
    exit();
}

if(!checkToken($data->user, $data->key)) {
    echo '30';
    exit();
}

$replies = getReplies($data->parent);
$replies_arr = [];
foreach($replies as $reply) {
    rewriteAuthor($reply);
    array_push($replies_arr, $reply);
}

echo json_encode($replies_arr);