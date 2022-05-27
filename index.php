<?php
include_once("User.php");


// echo $_SERVER['REQUEST_URI'];
// $_SERVER['REQUEST_METHOD'] === 'POST'

// create user/sign up -- POST
// print("<pre>" . print_r($_POST, true) . "</pre>");
if ($_GET["action"] == "createUser" && $_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $dob = $_POST["dob"];
    $password = $_POST["password"];
    $profile_pic = $_POST["profile_pic"];
    $gender = $_POST["gender"];

    $user = new User();
    $affected_rows = $user->createUser(
        $email,
        $first_name,
        $last_name,
        $dob,
        $password,
        $profile_pic,
        $gender
    );
    echo $affected_rows;
}

// login user -- POST
// get user -- GET
// get users -- GET

// login admin -- POST

// create restaurant -- POST
// get restaurants -- GET
// get restaurant -- GET

// create review -- POST
// update review -- POST
// get reviews -- GET
// get review -- GET






// echo json_encode($returned);
