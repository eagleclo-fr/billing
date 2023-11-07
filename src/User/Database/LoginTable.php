<?php
namespace src\User\Database;

use src\Database\Database;

class LoginTable {

    public function __construct() {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
    }

    public function checkLogin(string $mail, string $password) {
        $this->select = $this->pdo->prepare('SELECT * FROM `users` WHERE `mail` = :mail AND `password` = :password');
        $this->select->bindValue(':mail', $mail, $this->pdo::PARAM_STR);
        $this->select->bindValue(':password', $password, $this->pdo::PARAM_STR);
        $this->select->execute();
        $this->checkExist = $this->select->rowCount();
        $this->resultInfo = $this->select->fetch();

        if($this->checkExist == 1) {

            $this->userid = $this->resultInfo['id'];
            $this->confirm = $this->resultInfo['confirm'];
            $this->banned = $this->resultInfo['banned'];
            $this->id_customer = $this->resultInfo['id_customer'];

        }

    }

}