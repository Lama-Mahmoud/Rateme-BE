<?php

use LDAP\Result;

include_once("User.php");
include_once("Admin.php");
include_once("Restaurant.php");
include_once("Review.php");

// echo $_SERVER['REQUEST_URI'];
// $_SERVER['REQUEST_METHOD'] === 'POST'

// print("<pre>" . print_r($_POST, true) . "</pre>");

// create user/sign up -- POST
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
if ($_GET["action"] == "loginUser" && $_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $user = new User();
    $result = $user->loginUser($email, $password);
    if (!$result) {
        header('HTTP/1.1 403');
        die();
    }
    echo $result["user_id"];
}

// get one user -- GET
if ($_GET["action"] == "getOneUser" && $_SERVER["REQUEST_METHOD"] === "GET") {

    $user_id = $_GET["user_id"];

    $user = new User();
    $result = $user->getOneUser($user_id);

    echo json_encode($result);
}

// get users -- GET
if ($_GET["action"] == "getUsers" && $_SERVER["REQUEST_METHOD"] === "GET") {
    $user = new User();
    $result = $user->getUsers();

    echo json_encode($result);
}

///////////////////////////////
// creat admin -- POST
if ($_GET["action"] == "createAdmin" && $_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $user = new Admin();
    $affected_rows = $user->createAdmin(
        $email,
        $password,
    );
    echo $affected_rows;
}
// login admin -- POST
if ($_GET["action"] == "loginAdmin" && $_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $admin = new Admin();
    $result = $admin->loginAdmin($email, $password);
    if (!$result) {
        header('HTTP/1.1 403');
        die();
    }
    echo $result["admin_id"];
}

///////////////////////////////////
// create restaurant -- POST
if ($_GET["action"] == "createRestaurant" && $_SERVER["REQUEST_METHOD"] === "POST") {

    $rest_name = $_POST["rest_name"];
    $rest_desc = $_POST["rest_desc"];
    $rest_pic = $_POST["rest_pic"];

    $restaurant = new Restaurant();
    $affected_rows = $restaurant->createRestaurant(
        $rest_name,
        $rest_desc,
        $rest_pic
    );
    echo $affected_rows;
}
// get one restaurant -- GET
if ($_GET["action"] == "getOneRestaurant" && $_SERVER["REQUEST_METHOD"] === "GET") {

    $rest_id = $_GET["rest_id"];

    $user = new Restaurant();
    $result = $user->getOneRestaurant($rest_id);

    echo json_encode($result);
}
// get restaurants -- GET
if ($_GET["action"] == "getRestaurants" && $_SERVER["REQUEST_METHOD"] === "GET") {
    $restaurant = new Restaurant();
    $result = $restaurant->getRestaurants();

    echo json_encode($result);
}

// create review -- POST
if ($_GET["action"] == "createReview" && $_SERVER["REQUEST_METHOD"] === "POST") {

    $user_id = $_POST["user_id"];
    $rest_id = $_POST["rest_id"];
    $review_content = $_POST["review_content"];
    $rate = $_POST["rate"];

    $review = new Review();
    $affected_rows = $review->createReview(
        $user_id,
        $rest_id,
        $review_content,
        $rate
    );
    echo $affected_rows;
}
// update review status-- POST
// get reviews -- GET
// get review -- GET

// echo json_encode($returned);