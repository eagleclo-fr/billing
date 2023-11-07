<?php
namespace src\User\Database;

use src\Database\Database;

class UserTable
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
    }

    public function checkUserExist(int $userid)
    {
        $this->select = $this->pdo->prepare('SELECT * FROM `users` WHERE `id` = :id');
        $this->select->bindValue(':id', $userid, $this->pdo::PARAM_INT);
        $this->select->execute();
        $this->checkExist = $this->select->rowCount();
    }

    public function getInfo(int $userid)
    {
        $this->select = $this->pdo->prepare('SELECT * FROM `users` WHERE `id` = :id');
        $this->select->bindValue(':id', $userid, $this->pdo::PARAM_INT);
        $this->select->execute();
        $this->result = $this->select->fetch();
        $this->checkExist = $this->select->rowCount();

        $this->id = $this->result['id'];
        $this->firstname = $this->result['firstname'];
        $this->lastname = $this->result['lastname'];
        $this->customerid = $this->result['id_customer'];
        $this->mail = $this->result['mail'];
        $this->created_at = date('d/m/Y H:i', strtotime($this->result['date_created']));

        $this->address = $this->result['address'];
        $this->city = $this->result['city'];
        $this->region = $this->result['region'];
        $this->country = $this->result['country'];

        $this->solde = $this->result['credits'];
        $this->role = $this->result['role'];
    }

    public function UserByEmailTable(string $mail)
    {
        $this->select = $this->pdo->prepare('SELECT * FROM `users` WHERE `mail` = :mail');
        $this->select->bindValue(':mail', $mail, $this->pdo::PARAM_STR);
        $this->select->execute();
        $this->checkExist = $this->select->rowCount();
        $this->result = $this->select->fetch();
        $this->userid = $this->result['id'];
    }

    public function editUserTable(int $userid, string $firstname, string $lastname, string $address, string $city, string $region, string $country){
        $this->update = $this->pdo->prepare('UPDATE `users` SET firstname = :firstname, lastname = :lastname, address = :address, city = :city, region = :region, country = :country WHERE `id` = :id');
        $this->update->bindValue(':firstname', $firstname, $this->pdo::PARAM_STR);
        $this->update->bindValue(':lastname', $lastname, $this->pdo::PARAM_STR);
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

    public function updateUserSoldeTable(int $userid, string $afterSolde){
        $this->update = $this->pdo->prepare('UPDATE `users` SET credits = :credits WHERE `id` = :id');
        $this->update->bindValue(':credits', $afterSolde, $this->pdo::PARAM_STR);
        $this->update->bindValue(':id', $userid, $this->pdo::PARAM_INT);
        $this->update->execute();
    }

}