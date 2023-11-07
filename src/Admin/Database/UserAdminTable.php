<?php
namespace src\Admin\Database;

use src\Database\Database;

class UserAdminTable
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
    }

    public function editUserTable(int $userid, string $firstname, string $lastname, string $address, string $city, string $region, string $country, string $credit, string $mail){
        $this->update = $this->pdo->prepare('UPDATE `users` SET firstname = :firstname, lastname = :lastname, mail = :mail, credits = :credits, address = :address, city = :city, region = :region, country = :country WHERE `id` = :id');
        $this->update->bindValue(':firstname', $firstname, $this->pdo::PARAM_STR);
        $this->update->bindValue(':lastname', $lastname, $this->pdo::PARAM_STR);
        $this->update->bindValue(':mail', $mail, $this->pdo::PARAM_STR);
        $this->update->bindValue(':credits', $credit, $this->pdo::PARAM_STR);
        $this->update->bindValue(':address', $address, $this->pdo::PARAM_STR);
        $this->update->bindValue(':city', $city, $this->pdo::PARAM_STR);
        $this->update->bindValue(':region', $region, $this->pdo::PARAM_STR);
        $this->update->bindValue(':country', $country, $this->pdo::PARAM_STR);
        $this->update->bindValue(':id', $userid, $this->pdo::PARAM_INT);
        $this->update->execute();
    }

    public function updatePasswordTable(int $userid, string $password){
        $this->update = $this->pdo->prepare('UPDATE `users` SET password = :password WHERE `id` = :id');
        $this->update->bindValue(':password', sha1($password), $this->pdo::PARAM_STR);
        $this->update->bindValue(':id', $userid, $this->pdo::PARAM_INT);
        $this->update->execute();
    }

    public function getAllSession(int $userid) {
        $this->select = $this->pdo->prepare('SELECT COUNT(id) FROM `sessions` WHERE `userid` = :userid');
        $this->select->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->select->execute();
        $this->stats = $this->select->fetch(\PDO::FETCH_NUM)[0];
    }

}