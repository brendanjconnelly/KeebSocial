<?php
require_once getenv("PHP_ROOT") . "/api/helper/USER.php";
require_once getenv("PHP_ROOT") . "/api/helper/DB.php";

function getPostField($uuid, $field) {
    global $keebsocial_content;
    return $keebsocial_content->post->findOne(['uuid' => $uuid])->{$field};
}

function pullPostArray($uuid, $field, $content) {
    global $keebsocial_content;
    $keebsocial_content->posts->updateOne(
        ['uuid' => $uuid],
        ['$pull' => [
            $field => $content
        ]]
    );
}

function pushPostArray($uuid, $field, $content) {
    global $keebsocial_content;
    $keebsocial_content->posts->updateOne(
        ['uuid' => $uuid],
        ['$push' => [
            $field => $content
        ]]
    );
}

function createPost($username, $content) {
    global $keebsocial_content;
    if(strcmp($content, '') == 0) return 1;
    $uuid = uniqid();
    $keebsocial_content->posts->insertOne([
        'uuid' => $uuid,
        'author' => getUserField($username, 'uuid'),
        'content' => htmlspecialchars($content),
        'parent' => '',
        'replies' => [],
        'likes' => [],
        'reposts' => [],
        'likes_count' => 0,
        'reposts_count' => 0,
        'replies_count' => 0,
        'date' => time()
    ]);
    return $uuid;
}

function deletePost($uuid) {
    global $keebsocial_content;
    return $keebsocial_content->posts->deleteOne(
        ['uuid' => $uuid]
    );
}

// uuid of parent keeb
function reply($username, $content, $uuid) {

}

function getPost($uuid) {
    global $keebsocial_content;
    return $keebsocial_content->posts->findOne(['uuid' => $uuid]);
}

// index 0 is most recent
function getPostByIndex($usernameArr, $index) {
    if(!(is_array($usernameArr))) {
        echo 'username must be array in getPostByIndex';
        return;
    }
    global $keebsocial_content;

    $dbresp = $keebsocial_content->posts->find(
        ['$or' => buildUsernameQuery($usernameArr)],
        [
            'sort' => ['date' => -1],
            'limit' => $index+1
        ]
    );
    $i = 0;
    foreach($dbresp as $post) {
        if($i == $index) {
            return $post;
        }
        $i++;
    }
}

function getPostCount($usernameArr) {
    if(!(is_array($usernameArr))) {
        echo 'username must be array in getPostByIndex';
        return;
    }
    global $keebsocial_content;

    return $keebsocial_content->posts->count(
        ['$or' => buildUsernameQuery($usernameArr)]
    );
}

$validPostFields = [
    "uuid",
    "author",
    "parent",
    "likes_count",
    "reposts_count",
    "replies_count"
];

$validPostArrays = [
    "replies",
    "likes",
    "reposts"
];

function isValidPostField($field) {
    global $validPostFields;
    return in_array($field, $validPostFields);
}