<?php

// Class to connect to database
class Database{

    // Database info
    private $host = "localhost"; // host name
    private $username = "root"; // username
    private $password = ""; // password
    private $db = "association_database_v3"; // database name
    public $last_insert_id;


    // Connects to Database
    function connect(){
        $connection = mysqli_connect($this->host, $this->username, $this->password, $this->db);
        return $connection;
    }

    // Reads data from Database
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

    // Saves data to Database
    function save($query){
        $conn = $this->connect();
        $result = mysqli_query($conn, $query); // return true or false if query worked or not

        // If query fails, return false. Else, return true
        if ($result == false){
            return false;
        } else{
            $this->last_insert_id = mysqli_insert_id($conn);
            return true;
        }
    }

    // Save data to Database through prepared statements
    function save_prepared($query, $types, $params){
        $conn = $this->connect();
        $stmt = mysqli_stmt_init($conn);

        // Prepare the statement
        if (!mysqli_stmt_prepare($stmt, $query)){
            return false;
        } else{
            // Bind parameters to the placeholder
            mysqli_stmt_bind_param($stmt, $types, ...$params);

            // Execute the statement
            mysqli_stmt_execute($stmt);

            // Close the statement
            mysqli_stmt_close($stmt);

            $this->last_insert_id = mysqli_insert_id($conn);

            return true;
        }
    }

}
?>