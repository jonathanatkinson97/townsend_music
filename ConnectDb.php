<?php

class ConnectDb {

    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $name = 'townsend_music';
    
    // The db connection is established in the private constructor.
    private function __construct()
    {
        $this->conn = new PDO(
            "mysql:host={$this->host};dbname={$this->name}", 
            $this->user,
            $this->pass,
        );
    }

    public static function getInstance()
    {
        if(!self::$instance) {
            self::$instance = new ConnectDb();
        }
  
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->conn;
    }
}