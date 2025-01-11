<?php

/**
 * FEEDQUERY.php
 * @param user
 * @param field
 * @return holyhell
 */
require_once getenv("PHP_ROOT") . "/api/helper/USER.php";

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->user) || !isset($data->field)) {
    echo '10';
    exit();
}

if((strcmp($data->field, "follows") != 0) && (strcmp($data->field, "followers") != 0)) {
    echo '10';
    exit();
}

$uuidarr = getUserArray($data->user, $data->field);
$namearr = [];
foreach($uuidarr as $uuid) {
    array_push($namearr, htmlspecialchars(getUsername($uuid)));
}

echo json_encode($namearr);