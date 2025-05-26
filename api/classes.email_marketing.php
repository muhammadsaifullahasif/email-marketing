<?php

require_once('./classes.email_marketing.php');

class email_marketing {
    
    private $hostname = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'email_marketing';
    private $conn;
    private $result = array();

    public function __construct() {
        $this->conn = mysqli_connect($hostname, $username, $password, $dbname);
    }

    public function conn() {
        return $this->conn;
    }

    public function sql($query) {

        $query = mysqli_query($this->conn, $sql);

    }

}