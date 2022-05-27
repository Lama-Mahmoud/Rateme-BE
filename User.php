<?php
include_once("Database.php");

class User extends Database
{
    private $table = "users";

    public function createUser($email, $first_name, $last_name, $dob, $password, $profile_pic, $gender)
    {
        $hashed_password = hash("sha256", $password);
        $query_string = "INSERT INTO $this->table 
        (email, first_name, last_name, dob, pswd_hash, profile_pic, gender) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $validation_string = "ssssssi";
        $params = [$email, $first_name, $last_name, $dob, $hashed_password, $profile_pic, $gender];
        $result = $this->query($query_string, $validation_string, $params);
        return $result["affected_rows"];
    }

    public function getUsers()
    {
    }
    public function getOneUser()
    {
    }
    public function loginUser()
    {
    }
}
