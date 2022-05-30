<?php
require_once(__DIR__ . "/../vendor/autoload.php");
include_once(__DIR__ . "/../config/jwt_key.php");

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

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
    try {
        $decoded = JWT::decode($JWT, new Key(JWT_KEY, 'HS256'));
        if ($decoded) {
            $payload = json_decode(json_encode($decoded), true);

            if ($payload["exp"] > time() && $payload["user_id"] == $user_id) {
                return true;
            } else {
                return false;
            }
        }
    } catch (Exception $e) {
        return false;
    }
}

function extractToken($headers)
{
    $auth = $headers["Authorization"];
    // preg_match('/Bearer\s(\S+)/', $auth, $matches);
    // return $matches[1];
    return explode(" ", $auth)[1];
}
