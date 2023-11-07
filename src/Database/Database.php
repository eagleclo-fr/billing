<?php
namespace src\Database;

class Database {

    private $connection;

    public function __construct(){
        $this->username = Credentials::$credentials['username'];
        $this->password = Credentials::$credentials['password'];
        $this->database = Credentials::$credentials['database'];
        $this->host = Credentials::$credentials['host'];
    }

    public function connect(){
        $this->mysql = "mysql:host=" . $this->host . ";dbname=" . $this->database;
        $this->connection = new \PDO("$this->mysql;charset=utf8", $this->username, $this->password);
        return $this->connection;
    }

    public function __destruct(){
        $this->connection = NULL;
    }

}