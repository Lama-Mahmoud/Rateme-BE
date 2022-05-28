<?php
function decodeBase64($base64_image_data)
{
    $img_data = explode(",", $base64_image_data)[1];
    $img = base64_decode($img_data);
    return $img;
}

function saveImage($decoded_image_date, $identifier, $id)
{
    $date = new DateTime();
    $path = "images/$identifier/$id-$date->getTimestamp()";
    file_put_contents($path, $decoded_image_date);
    return $path;
}

function encodeBase64($image_path)
{
}
