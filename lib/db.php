<?php

class Db {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "mg";
    public function __construct() {
       // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    }
    static public function crud($sql){
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        return $conn->close();
    }
}

