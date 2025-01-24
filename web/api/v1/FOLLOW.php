<?php

/**
 * FOLLOW.php
 * Used to follow or unfollow an account
 * @param user invoking user
 * @param key auth token
 * @param target user to follow
 * @param follow=1 if explicitly set 0, this will unfollow the user
 * @return 0 on success, 1 on bad params, 2 on target dne, 3 on bad auth, 4 on nop
 */
require_once getenv("PHP_ROOT") . "/api/helper/RC.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER.php";
require_once getenv("PHP_ROOT") . "/api/helper/DB.php";
require_once getenv("PHP_ROOT") . "/api/helper/USER_AUTH.php";

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->key) || !isset($data->target) || !isset($data->user)) {
    echo $BAD_PARAMS;
    exit();
}
if(!cUserExists($data->target) || !cUserExists($data->user)) {
    echo $DNE;
    exit();
}
if(!checkToken($data->user, $data->key)) {
    echo $UNAUTHORIZED;
    exit();
}
if(strcmp($data->user, $data->target) == 0) {
    echo $BAD_FIELD;
    exit();
}


if(isset($data->follow) && ($data->follow == 0)) { // if unfollow mode
    pullUserArray($data->user, "follows", getUserField($data->target, "uuid")); // user follows target
    pullUserArray($data->target, "followers", getUserField($data->user, "uuid")); // target is followed by user
    setUserField($data->user, "follows_count", getUserField($data->user, "follows_count") - 1);
    setUserField($data->target, "followers_count", getUserField($data->target, "followers_count") - 1);
    echo $SUCCESS
    exit();
}
// else follow

// but not if we already follow
if(doesUserFollow($data->user, $data->target)) {
    echo $BAD_ARGUMENT;
    exit();
}

pushUserArray($data->user, "follows", getUserField($data->target, "uuid")); // user follows target
pushUserArray($data->target, "followers", getUserField($data->user, "uuid")); // target is followed by user
setUserField($data->user, "follows_count", getUserField($data->user, "follows_count") + 1);
setUserField($data->target, "followers_count", getUserField($data->target, "followers_count") + 1);

echo $SUCCESS;

?>