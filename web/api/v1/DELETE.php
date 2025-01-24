<?php

/**
 * DELETE.php
 * Used for deleting a post
 * @param user
 * @param key
 * @param post post uuid
 */

require_once getenv("PHP_ROOT") . "/api/helper/RC.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";
require_once getenv("PHP_ROOT") . "/api/helper/POST.php";

$data = json_decode(file_get_contents("php://input"));
if(!isset($data->user) || !isset($data->key) || !isset($data->post)) {
    echo $BAD_PARAMS;
    exit();
}

if(!checkToken($data->user, $data->key)) {
    echo $UNAUTHORIZED;
    exit();
}

$post = getPost($data->post);

if(is_null($post)) {
    echo $DNE;
    exit();
}

$res = deletePost($data->post);
var_dump($res);
echo '0';