<?php

/**
 * ACT.php
 * @param user username
 * @param key token
 * @param action POST|LIKE|REPLY|REKEEB
 * @param uuid[opt] uuid of target
 * @param content[opt] only for action=POST|REPLY
 * @return uuid of the post if applicable
 */
require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER.php";
require_once getenv("PHP_ROOT") . "/api/helper/DB.php";
require_once getenv("PHP_ROOT") . "/api/helper/POST.php";

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->user) || !isset($data->key) || !isset($data->action)) {
    echo '10';
    exit();
}

if(!checkToken($data->user, $data->key)) {
    echo '30';
    exit();
}

if(strcmp($data->action, "POST") == 0) {
    $uuid = createPost($data->user, $data->content);
    echo $uuid;
    exit();
}

if($data->action == "REPLY") {
    echo createPost($data->user, $data->content, $data->uuid);
    exit();
}

echo '0';