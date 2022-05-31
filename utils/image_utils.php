<?php
function decodeBase64($base64_image_data)
{
    $img_data = explode(",", $base64_image_data)[1];
    $img = base64_decode($img_data);
    return $img;
}

function saveImage($picture, $category, $identifier)
{
    $timestamp = time();
    $temp_path = $picture["tmp_name"];
    $extension = explode("/", $picture["type"])[1];
    $new_path = "images/$category/$identifier-$timestamp.$extension";
    move_uploaded_file($temp_path, $new_path);
    // file_put_contents($path, $decoded_image_date);
    return $new_path;
}

function encodeBase64($image_path)
{
    $img = file_get_contents($image_path);
    $img_encoded = base64_encode($img);
    $img_encoded = "data:image/jpeg;base64," . $img_encoded;
    return $img_encoded;
}
