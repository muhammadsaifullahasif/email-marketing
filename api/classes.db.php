<?php

class db {

    private $hostname = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'email_marketing';
    private $conn;

    public function __construct() {
        $this->conn = mysqli_connect($hostname, $username, $password, $dbname);
    }

    public function conn() {
        return $this->conn;
    }

}