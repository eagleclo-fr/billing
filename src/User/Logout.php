<?php
namespace src\User;

use src\Database\Database;
use src\User\Session;

class Logout {

    public function __construct(){
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->session = new Session();

    }

    public function logoutUser()
    {
        $this->session->terminateSession();
    }
}
