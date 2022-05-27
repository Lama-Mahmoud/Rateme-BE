<?php
include_once("db_config.php");
class Database
{
    // class properties
    private $server;
    private $user;
    private $password;
    private $name;
    private $connection;
    // constructor with default arguments as the constants that 
    // represent the db configurations, imported from the db_config.php file
    function __construct($server = DB_SERVER, $user = DB_USER, $password = DB_PASSWORD, $name = DB_NAME)
    // function __construct()
    {
        // initializing class properties
        $this->server = $server;
        $this->user = $user;
        $this->password = $password;
        $this->name = $name;

        // initializing the class "connection" property as mysqli instance
        $this->connection = new mysqli($this->server, $this->user, $this->password, $this->name);
        // killing the php process in case of an error and returning the error
        if ($this->connection->connect_error) {
            die($this->connection->connect_error);
        }
    }

    // class method that is accessible from subclasses in case of adding new subclasses
    // by which each added subclass will represent a table in the db
    public function query($query_string, $params_string, ...$params)
    {
        // preparing the query
        $prepared = $this->connection->prepare($query_string);
        // in case of passing a $params assoc array (param => type)
        // looping over it and binding the values to the 
        if (count($params) > 0) {
            // foreach ($params as $type => $param) {
            $prepared->bind_param($params_string, ...$params);
            // }
        }
        // executing the query, getting results, and loop over the 
        // fetched records to construct the array to be returned
        $prepared->execute();
        $result = $prepared->get_result();
        $rows = [];
        while ($record = $result->fetch_assoc()) {
            $rows[] = $record;
        }
        return $rows;
    }
}
