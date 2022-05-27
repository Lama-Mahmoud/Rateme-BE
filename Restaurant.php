<?php
include_once("Database.php");

class Restaurant extends Database
{
    private $table = "restaurants";

    public function createRestaurant($rest_name, $rest_desc, $rest_pic)
    {
        $query_string = "INSERT INTO $this->table 
                        (rest_name, rest_desc, rest_pic) 
                        VALUES (?, ?, ?)";
        $validation_string = "sss";
        $params = [$rest_name, $rest_desc, $rest_pic];
        $result = $this->query($query_string, $validation_string, $params);
        return $result["affected_rows"];
    }
    public function getOneRestaurant($rest_id)
    {
        $query_string = "SELECT rest_name, rest_desc, rest_pic
                        FROM $this->table
                        WHERE rest_id = ?";
        $validation_string = "i";
        $params = [$rest_id];
        $result = $this->getRow($query_string, $validation_string, $params);
        return $result;
    }
    public function getRestaurants()
    {
    }
}
