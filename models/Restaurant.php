<?php
include_once("Database.php");

class Restaurant extends Database
{
    // private class property, which is the table that the Restaurant interact with
    private $table = "restaurants";


    public function createRestaurant($rest_name, $rest_desc, $rest_pic)
    {
        $query_string = "INSERT INTO $this->table 
                        (rest_name, rest_desc, rest_pic) 
                        VALUES (?, ?, ?)";
        $validation_string = "sss";
        $params = [$rest_name, $rest_desc, $rest_pic];
        // using the inherited method to make a query
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
        // using the inherited method to get one record
        $result = $this->getRow($query_string, $validation_string, $params);
        return $result;
    }
    public function getRestaurants()
    {
        $query_string = "SELECT rest_id, rest_name, rest_desc, rest_pic
                        FROM $this->table";
        // using the inherited method to get multiple records
        $result = $this->getRows($query_string);
        return $result;
    }
}
