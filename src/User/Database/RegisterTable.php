<?php
namespace src\User\Database;

use src\Database\Database;

class RegisterTable {

    public function __construct(){
        $this->database = New Database();
        $this->pdo = $this->database->connect();
    }

    public function checkUserExist($mail){
        $this->select = $this->pdo->prepare('SELECT * FROM `users` WHERE `mail` = :mail');
        $this->select->bindValue(':mail', $mail, $this->pdo::PARAM_STR);
        $this->select->execute();
        $this->checkMail = $this->select->rowCount();
    }

    public function createUserTable($id_customer, $mail, $password, $key, $ip) {
        $this->insert = $this->pdo->prepare('INSERT INTO `users` SET id_customer = :id_customer, mail = :mail, password = :password, credits = :credits, date_created = :date_created, confirmkey = :confirmkey, ip_address = :ip_address');
        $this->insert->bindValue(':id_customer', $id_customer, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':mail', $mail, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':password', $password, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':credits', '0.00', $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_created', date('d-m-Y H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->bindValue(':confirmkey', $key, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':ip_address', $ip, $this->pdo::PARAM_STR);
        $this->insert->execute();
        $this->userid = $this->pdo->lastInsertId();
    }


}