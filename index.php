<?php
include_once("models/User.php");
include_once("models/Admin.php");
include_once("models/Restaurant.php");
include_once("models/Review.php");
include_once("utils/image_utils.php");
include_once("utils/token_utils.php");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
$headers = getallheaders();

// create user/sign up -- POST
if ($_GET["action"] == "createUser" && $_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset(
            $_POST["email"],
            $_POST["first_name"],
            $_POST["last_name"],
            $_POST["dob"],
            $_POST["password"],
            $_FILES["profile_pic"],
            $_POST["gender"]
        )
    ) {
        $email = $_POST["email"];
        $first_name = $_POST["first_name"];
        $last_name = $_POST["last_name"];
        $dob = $_POST["dob"];
        $password = $_POST["password"];
        $profile_pic = $_FILES["profile_pic"];
        $gender = $_POST["gender"];
    } else die("missing values");

    $profile_pic_path = saveImage($profile_pic, "user", $email);

    $user = new User();
    $affected_rows = $user->createUser(
        $email,
        $first_name,
        $last_name,
        $dob,
        $password,
        $profile_pic_path,
        $gender
    );
    echo $affected_rows;
}
//update user -- POST
if ($_GET["action"] == "updateUser" && $_SERVER["REQUEST_METHOD"] === "POST") {

    isset($_POST["user_id"]) ? $user_id = $_POST["user_id"] : die("missing values");
    $jwt = extractToken($headers["Authorization"]);
    [$is_auth, $user_type] = authenticateToken($jwt, $user_id);
    if ($is_auth && $user_type == "user") {
        if (
            isset(
                $_POST["email"],
                $_POST["first_name"],
                $_POST["last_name"],
                $_POST["dob"],
                $_FILES["profile_pic"],
                $_POST["gender"]
            )
        ) {
            $email = $_POST["email"];
            $first_name = $_POST["first_name"];
            $last_name = $_POST["last_name"];
            $dob = $_POST["dob"];
            $profile_pic = $_FILES["profile_pic"];
            $gender = $_POST["gender"];
        } else die("missing values");

        $profile_pic_path = saveImage($profile_pic, "user", $email);

        $user = new User();
        $affected_rows = $user->updateUser(
            $user_id,
            $email,
            $first_name,
            $last_name,
            $dob,
            $profile_pic_path,
            $gender
        );
        echo $affected_rows;
    } else {
        header('HTTP/1.1 403');
        die("Access denied");
    }
}
// login user -- POST
if ($_GET["action"] == "loginUser" && $_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["email"], $_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
    } else die("missing values");

    $user = new User();
    $result = $user->loginUser($email, $password);
    if (!$result) {
        header('HTTP/1.1 403');
        die("Invalid credentials");
    }
    $user_id = $result["user_id"];
    $jwt = generateToken($user_id, "user");
    echo json_encode(["user_id" => $user_id, "token" => $jwt]);
}

// get one user -- GET
if ($_GET["action"] == "getOneUser" && $_SERVER["REQUEST_METHOD"] === "GET") {

    isset($_GET["user_id"]) ? $user_id = $_GET["user_id"] : die("missing values");
    $jwt = extractToken($headers);
    [$is_auth, $user_type] = authenticateToken($jwt, $user_id);
    if ($is_auth && ($user_type == "admin" || $user_type == "user")) {
        $user = new User();
        $result = $user->getOneUser($user_id);
        echo json_encode($result);
    } else {
        header('HTTP/1.1 403');
        die("Access denied");
    }
}

// get users -- GET
if ($_GET["action"] == "getUsers" && $_SERVER["REQUEST_METHOD"] === "GET") {

    isset($_GET["admin_id"]) ? $admin_id = $_GET["admin_id"] : die("missing values");
    $jwt = extractToken($headers);
    [$is_auth, $user_type] = authenticateToken($jwt, $admin_id);
    if ($is_auth && $user_type == "admin") {
        $user = new User();
        $result = $user->getUsers();

        echo json_encode($result);
    } else {
        header('HTTP/1.1 403');
        die("Access denied");
    }
}

///////////////////////////////
// create admin -- POST
if ($_GET["action"] == "createAdmin" && $_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["email"], $_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
    } else die("missing values");

    $admin = new Admin();
    $affected_rows = $admin->createAdmin(
        $email,
        $password,
    );
    echo $affected_rows;
}
// login admin -- POST
if ($_GET["action"] == "loginAdmin" && $_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["email"], $_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
    } else die("missing values");

    $admin = new Admin();
    $result = $admin->loginAdmin($email, $password);
    if (!$result) {
        header('HTTP/1.1 403');
        die();
    }
    $admin_id = $result["admin_id"];
    $jwt = generateToken($admin_id, "admin");
    echo json_encode(["admin_id" => $admin_id, "token" => $jwt]);
}

///////////////////////////////////
// create restaurant -- POST
if ($_GET["action"] == "createRestaurant" && $_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["rest_name"], $_POST["rest_desc"], $_GET["admin_id"], $_FILES["rest_pic"])) {
        $admin_id = $_GET["admin_id"];
        $rest_name = $_POST["rest_name"];
        $rest_desc = $_POST["rest_desc"];
        $rest_pic = $_FILES["rest_pic"];
    } else die("missing values");
    $jwt = extractToken($headers);
    [$is_auth, $user_type] = authenticateToken($jwt, $admin_id);
    if ($is_auth && $user_type == "admin") {
        $rest_pic_path = saveImage($rest_pic, "restaurant", $rest_name);
        $restaurant = new Restaurant();
        $affected_rows = $restaurant->createRestaurant(
            $rest_name,
            $rest_desc,
            $rest_pic_path
        );
        echo $affected_rows;
    } else {
        header('HTTP/1.1 403');
        die("Access denied");
    }
}
// get one restaurant -- GET
if ($_GET["action"] == "getOneRestaurant" && $_SERVER["REQUEST_METHOD"] === "GET") {

    if (isset($_GET["rest_id"], $_GET["user_id"])) {
        $rest_id = $_GET["rest_id"];
        $user_id = $_GET["user_id"];
    } else {
        die("missing values");
    }
    $jwt = extractToken($headers);
    [$is_auth, $user_type] = authenticateToken($jwt, $user_id);
    if ($is_auth && $user_type == "user") {
        $user = new Restaurant();
        $result = $user->getOneRestaurant($rest_id);

        echo json_encode($result);
    } else {
        header('HTTP/1.1 403');
        die("Access denied");
    }
}
// get restaurants -- GET
if ($_GET["action"] == "getRestaurants" && $_SERVER["REQUEST_METHOD"] === "GET") {

    isset($_GET["admin_id"]) ? $rest_id = $_GET["rest_id"] : die("missing values");
    $jwt = extractToken($headers);
    [$is_auth, $user_type] = authenticateToken($jwt, $admin_id);
    if ($is_auth && $user_type == "admin") {
        $restaurant = new Restaurant();
        $result = $restaurant->getRestaurants();

        echo json_encode($result);
    }
}

////////////////////////////////
// create review -- POST
if ($_GET["action"] == "createReview" && $_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["user_id"], $_POST["user_id"], $_POST["review_content"], $_POST["rate"])) {
        $user_id = $_POST["user_id"];
        $rest_id = $_POST["rest_id"];
        $review_content = $_POST["review_content"];
        $rate = $_POST["rate"];
    } else die("missing values");

    $jwt = extractToken($headers);
    [$is_auth, $user_type] = authenticateToken($jwt, $user_id);
    if ($is_auth && $user_type == "user") {
        $review = new Review();
        $affected_rows = $review->createReview(
            $user_id,
            $rest_id,
            $review_content,
            $rate
        );
        echo $affected_rows;
    } else {
        header('HTTP/1.1 403');
        die("Access denied");
    }
}
// update review status-- POST
if ($_GET["action"] == "updateReviewStatus" && $_SERVER["REQUEST_METHOD"] === "POST") {

    if (isset($_POST["review_id"], $_POST["new_status"], $_GET["admin_id"])) {
        $admin_id = $_GET["admin_id"];
        $review_id = $_POST["review_id"];
        $new_status = $_POST["new_status"];
    }
    $jwt = extractToken($headers);
    [$is_auth, $user_type] = authenticateToken($jwt, $admin_id);
    if ($is_auth && $user_type == "admin") {
        $review = new Review();
        $affected_rows = $review->updateReviewStatus(
            $review_id,
            $new_status
        );
        echo $affected_rows;
    } else {
        header('HTTP/1.1 403');
        die("Access denied");
    }
}
// get restaurant reviews -- GET
if ($_GET["action"] == "getAcceptedRestaurantReviews" && $_SERVER["REQUEST_METHOD"] === "GET") {
    $rest_id = $_GET["rest_id"];
    $review = new Review();
    $result = $review->getAcceptedRestaurantReviews($rest_id);

    echo json_encode($result);
}
// get reviews -- GET
if ($_GET["action"] == "getReviews" && $_SERVER["REQUEST_METHOD"] === "GET") {
    isset($_GET["admin_id"]) ? $admin_id = $_GET["admin_id"] : die("missing values");
    $jwt = extractToken($headers);
    [$is_auth, $user_type] = authenticateToken($jwt, $admin_id);
    if ($is_auth && $user_type == "admin") {
        $review = new Review();
        $result = $review->getReviews();

        // echo json_encode($result);
    } else {
        header('HTTP/1.1 403');
        die("Access denied");
    }

    echo json_encode($result);
}

//get pending reviews -- GET
// if ($_GET["action"] == "getPendingReviews" && $_SERVER["REQUEST_METHOD"] === "GET") {
//     isset($_GET["admin_id"]) ? $admin_id = $_GET["admin_id"] : die("missing values");
//     $jwt = extractToken($headers);
//     [$is_auth, $user_type] = authenticateToken($jwt, $admin_id);
//     if ($is_auth && $user_type == "admin") {
//         $review = new Review();
//         $result = $review->getPendingReviews();

//         echo json_encode($result);
//     } else {
//         header('HTTP/1.1 403');
//         die("Access denied");
//     }

//     echo json_encode($result);
// }