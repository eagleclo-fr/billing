<?php
namespace src\Admin;

use src\User\Database\UserTable;
use src\Admin\Database\UserAdminTable;
use src\User\Session;
use src\Router\RouterHelper;
use src\Helper\FlashService;
use src\Database\Database;
use src\Helper\CSRF;

class UserAdmin
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->userTable = new UserTable();
        $this->userAdminTable = new UserAdminTable();
        $this->session = new Session();
        $this->router = new RouterHelper();
        $this->flash = new FlashService();
        $this->csrf = csrf::post();
    }

    public function getUser(int $userid){
        $this->userTable->getInfo($userid);

        if($this->userTable->checkExist == 1) {

            $this->id = $this->userTable->id;
            $this->firstname = $this->userTable->firstname;
            $this->lastname = $this->userTable->lastname;
            $this->customerid = $this->userTable->customerid;
            $this->mail = $this->userTable->mail;
            $this->created_at = $this->userTable->created_at;

            $this->address = $this->userTable->address;
            $this->city = $this->userTable->city;
            $this->region = $this->userTable->region;
            $this->country = $this->userTable->country;

            $this->solde = $this->userTable->solde;
            $this->role = $this->userTable->role;

            $this->userTable->getAllSession($userid);
            $this->countSession = $this->userTable->stats;

        } else {
            header('location: /admin/users');
        }
    }

    public function editUser(int $userid, string $firstname, string $lastname, string $address, string $city, string $region, string $country, string $credit, string $mail){
        $this->userAdminTable->editUserTable($userid, $firstname, $lastname, $address, $city, $region, $country, $credit, $mail);
        header('location: /admin/users?state=success');

    }
}