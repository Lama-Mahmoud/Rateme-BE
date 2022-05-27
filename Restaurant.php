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
    public function getOneRestaurant()
    {
    }
    public function getRestaurants()
    {
    }
}
