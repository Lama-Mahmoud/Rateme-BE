<?php
function decodeBase64($base64_image_data)
{
    $img_data = explode(",", $base64_image_data)[1];
    $img = base64_decode($img_data);
    return $img;
}

function saveImage($decoded_image_date, $category, $identifier)
{
    $timestamp = time();
    $path = "images/$category/$identifier-$timestamp";
    file_put_contents($path, $decoded_image_date);
    return $path;
}

function encodeBase64($image_path)
{
    $img = file_get_contents($image_path);
    $img_encoded = base64_encode($img);
    $img_encoded = "data:image/jpeg;base64," . $img_encoded;
    return $img_encoded;
}