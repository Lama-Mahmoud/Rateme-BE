<?php
// using the __DIR__ magic variable to construct the path
// of the autoload and jwt key for signing the token
require_once(__DIR__ . "/../vendor/autoload.php");
include_once(__DIR__ . "/../config/jwt_key.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function generateToken($UiD, $user_type)
{
    // constructing the payload that will be encoded and added to the token
    $payload = [
        "exp" => time() + 3600, // time to live for the token in 1 hour|3600 seconds
        "user_id" => $UiD,
        "user_type" => $user_type
    ];
    // returning the json web token
    return JWT::encode($payload, JWT_KEY, "HS256");
}

function authenticateToken($JWT, $user_id)
{
    try {
        $decoded = JWT::decode($JWT, new Key(JWT_KEY, 'HS256'));
        $payload = json_decode(json_encode($decoded), true);

        // checkin if the token has expired
        if ($payload["exp"] > time() && $payload["user_id"] == $user_id) {
            return [true, $payload["user_type"]];
        } else {
            return [false, $payload["user_type"]];
        }
    } catch (Exception $e) {
        return [false];
    }
}

// to extract the token from the authorizatio header "bearer token"
function extractToken($headers)
{
    $auth = $headers["Authorization"];
    // preg_match('/Bearer\s(\S+)/', $auth, $matches);
    // return $matches[1];
    return explode(" ", $auth)[1];
}
