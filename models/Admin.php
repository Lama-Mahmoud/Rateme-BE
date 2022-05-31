<?php
include_once("Database.php");

class Admin extends Database
{
    // private class property, which is the table that the Admin interact with
    private $table = "admins";

    // class method that is used to create a new admin
    public function createAdmin($email, $password)
    {
        // hashing the password
        $hashed_password = hash("sha256", $password);
        // constructing the query string
        $query_string = "INSERT INTO $this->table 
                        (email,pswd_hash) 
                        VALUES (?, ?)";
        $validation_string = "ss";
        $params = [$email, $hashed_password];
        // using the inherited method to make a query
        $result = $this->query($query_string, $validation_string, $params);
        return $result["affected_rows"]; // returning the number of affected rows
    }

    public function loginAdmin($email, $password)
    {
        $hashed_password = hash("sha256", $password);
        $query_string = "SELECT admin_id from $this->table
                        WHERE email = ? and pswd_hash = ?";
        $validation_string = "ss";
        $params = [$email, $hashed_password];
        $result = $this->getRow($query_string, $validation_string, $params);
        return $result;
    }
}
