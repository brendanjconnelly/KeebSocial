<?php
require_once getenv("PHP_ROOT") . "/api/helper/DB.php";

/**
 * USER_AUTH.php
 * Utility functions for password database management
 */

/**
 * Checks if a username is registered
 * @return boolean Whether or not user is created
 */
function userExists($username) {
    global $keebsocial_users;
    $result = $keebsocial_users->users->count(['username' => strtolower($username)]);
    return ($result == 1); // Returns true if the user does exist
}

/**
 * Creates a user
 * @return JSON UUID and authentication token
 */
function createUser($username, $email, $password) {
    global $keebsocial_users;
    $password = _ksHash($password);
    $resp = $keebsocial_users->users->insertOne([
        'username' => strtolower($username),
        'email' => "",
        'hash' => $password,
        'tokens' => []
    ]);
}

/** 
 * Checks password
 * @return String Authentication token if valid password, else empty str
*/
function getNewToken($username, $password) {
    global $keebsocial_users;
    
    if(!authenticate($username, $password)) {
        return '0';
    }
    $token = _genToken();
    $expires = time() + (1 * 60 * 60 * 24 * 30); // 30 days from now
    $keebsocial_users->users->updateOne(
        ['username' => $username],
        ['$push' => array('tokens' => array('token' => $token, 'expires' => $expires))]
    );
    return $token;
}

function deleteToken($username, $token) {
    global $keebsocial_users;
    $keebsocial_users->users->updateOne(
        ['username' => $username],
        ['$pull' => ['tokens' => ['token' => $token]]]
    );
}
/**
 * Clears all authentication tokens
 */
function clearTokens($username) {
    global $keebsocial_users;

    $keebsocial_users->users->updateOne(
        ['username' => $username],
        ['$set' => array('tokens' => [])]
    );
}

/**
 * @return bool True if authenticated
 */
function checkToken($username, $authtoken) {
    global $keebsocial_users;
    $tokens = $keebsocial_users->users->findOne(['username' => $username])->tokens;
    foreach($tokens as $token) {
        if(strcmp($token->token, $authtoken) == 0) return true;
    }
    return false;
}

/**
 * @return bool True if password is correct
 */
function authenticate($username, $password) {
    global $keebsocial_users;

    $res = $keebsocial_users->users->findOne(
        ['username' => $username]
    );
 
    return (password_verify($password, $res->hash) == true);

}

/**
 * INTERNAL
 * Hashes password
 * @return String Hashed, salted password to be stored verbatim
 */
function _ksHash($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * INTERNAL
 * @return string 64-bit cryptographically secure pseudorandom number
 */
function _genToken() {
    return bin2hex(random_bytes(16));
}