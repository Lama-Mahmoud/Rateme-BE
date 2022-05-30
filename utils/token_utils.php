<?php
require_once(__DIR__ . "/../vendor/autoload.php");
include_once(__DIR__ . "/../config/jwt_key.php");

use Firebase\JWT\JWT;

function generateToken($UiD)
{
    $payload = [
        "exp" => time() + 3600,
        "user_id" => $UiD
    ];
    return JWT::encode($payload, JWT_KEY, "HS256");
}

function authenticateToken($JWT, $user_id)
{
    $decoded = JWT::decode($JWT, JWT_KEY, array("HS256"));
    $payload = json_decode(json_encode($decoded), true);

    if ($payload["exp"] < time() && $payload["user_id"] == $user_id) {
        return true;
    } else {
        return false;
    }
}

function extractToken($server_object)
{
    $auth = $server_object["Authorization"];
    return explode(" ", $auth)[0];
}
