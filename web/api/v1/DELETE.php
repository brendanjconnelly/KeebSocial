<?php

/**
 * DELETE.php
 * Used for deleting a post
 * @param user
 * @param key
 * @param post post uuid
 */
require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";
require_once getenv("PHP_ROOT") . "/api/helper/POST.php";

$data = json_decode(file_get_contents("php://input"));
if(!isset($data->user) || !isset($data->key) || !isset($data->post)) {
    echo '10';
    exit();
}

if(!checkToken($data->user, $data->key)) {
    echo '30';
    exit();
}

$post = getPost($data->post);

if(is_null($post)) {
    echo '100';
    exit();
}

$res = deletePost($data->post);
var_dump($res);
echo '0';