<?php

class Database{

    // Database info
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db = "association_database_v3";


    // Connects to db
    function connect(){
        $connection = mysqli_connect($this->host, $this->username, $this->password, $this->db);
        return $connection;
    }

    // Reads data from db
    function read($query){
        $conn = $this->connect();
        $result = mysqli_query($conn, $query); // return true or false if query worked or not

        // If query fails, return false. Else, add collect data
        if ($result == false){
            return false;
        } else{
            $data = [];
            while($row = mysqli_fetch_assoc($result)){
                $data[] = $row; 
            }

        return $data;
        }
    }

    // Saves data to db
    function save($query){
        $conn = $this->connect();
        $result = mysqli_query($conn, $query); // return true or false if query worked or not

        // If query fails, return false. Else, return true
        if ($result == false){
            return false;
        } else{
            return true;
        }
    }
}
?>