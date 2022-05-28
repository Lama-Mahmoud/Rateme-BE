<?php
include_once("config/db_config.php");
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

    // class method that is accessible from subclasses only
    protected function query($query_string, $params_string = "", $params = [])
    {
        // preparing the query
        $prepared = $this->connection->prepare($query_string);

        // spreading the params and binding them to the prepared
        if ($params) $prepared->bind_param($params_string, ...$params);

        // executing the query
        $prepared->execute();

        // returning an assoc array, the prepared key to be used in the select queries
        // the affected_rows to be used in the insertion queries
        return [
            'prepared' => $prepared,
            'affected_rows' => $this->connection->affected_rows
        ];
    }

    // class method that is accessible from subclasses only, to process select queries
    // and return all the rows
    protected function getRows($query_string, $params_string = "", $params = [])
    {
        $prepared = $this->query($query_string, $params_string, $params);
        $result = $prepared['prepared']->get_result();
        $rows = [];
        while ($record = $result->fetch_assoc()) $rows[] = $record;

        return $rows;
    }

    // class method that is accessible from subclasses only, to process select queries
    // that returns only one row
    protected function getRow($query_string, $params_string = "", $params = [])
    {
        $query_string .= " LIMIT 1";
        $rows = $this->getRows($query_string, $params_string, $params);
        if (count($rows) > 0) return $rows[0];
        return false;
    }

    // public function createNewUser($username, $password, $firstname, $lastname)
    // {
    //     $this->query($query_string, $params_string, $params);
    //     return $this->connection->affected_rows == 1 ? true : false;
    // }
}
