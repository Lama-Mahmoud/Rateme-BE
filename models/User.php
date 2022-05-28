<?php
include_once("Database.php");

class User extends Database
{
    private $table = "users";

    public function createUser($email, $first_name, $last_name, $dob, $password, $profile_pic, $gender)
    {
        $hashed_password = hash("sha256", $password);
        $query_string = "INSERT INTO $this->table 
                        (email, first_name, last_name, dob, pswd_hash, register_date, profile_pic, gender) 
                        VALUES (?, ?, ?, ?, ?, UTC_TIMESTAMP, ?, ?)";
        $validation_string = "ssssssi";
        $params = [$email, $first_name, $last_name, $dob, $hashed_password, $profile_pic, $gender];
        $result = $this->query($query_string, $validation_string, $params);
        return $result["affected_rows"];
    }

    public function updateUser($user_id, $email, $first_name, $last_name, $dob, $profile_pic, $gender)
    {
        $query_string = "UPDATE $this->table 
                        SET email = ?, first_name = ?, last_name = ?, dob = ?, profile_pic = ?, gender = ?
                        WHERE user_id = ?";
        $validation_string = "sssssii";
        $params = [$email, $first_name, $last_name, $dob, $profile_pic, $gender, $user_id];
        $result = $this->query($query_string, $validation_string, $params);
        return $result["affected_rows"];
    }

    public function loginUser($email, $password)
    {
        $hashed_password = hash("sha256", $password);
        $query_string = "SELECT user_id from $this->table
                        WHERE email = ? and pswd_hash = ?";
        $validation_string = "ss";
        $params = [$email, $hashed_password];
        $result = $this->getRow($query_string, $validation_string, $params);
        return $result;
    }

    public function getOneUser($user_id)
    {
        $query_string = "SELECT email, first_name, last_name, dob, register_date, profile_pic, gender
                        FROM $this->table
                        WHERE user_id = ?";
        $validation_string = "i";
        $params = [$user_id];
        $result = $this->getRow($query_string, $validation_string, $params);
        return $result;
    }
    public function getUsers()
    {
        $query_string = "SELECT email, first_name, last_name, dob, register_date, profile_pic, gender
                        FROM $this->table";
        $result = $this->getRows($query_string);
        return $result;
    }
}
