<?php
include_once("Database.php");

class Admin extends Database
{
    private $table = "admins";

    public function createAdmin($email, $password)
    {
        $hashed_password = hash("sha256", $password);
        $query_string = "INSERT INTO $this->table 
                        (email,pswd_hash) 
                        VALUES (?, ?)";
        $validation_string = "ss";
        $params = [$email, $hashed_password];
        $result = $this->query($query_string, $validation_string, $params);
        return $result["affected_rows"];
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
