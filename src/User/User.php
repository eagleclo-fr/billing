<?php
namespace src\User;

use src\User\Database\UserTable;
use src\User\Session;
use src\Router\RouterHelper;
use src\Helper\FlashService;
use src\Database\Database;
use src\Helper\CSRF;

class User {

    public function __construct(){
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->userTable = new UserTable();
        $this->session = new Session();
        $this->router = new RouterHelper();
        $this->flash = new FlashService();
        $this->csrf = csrf::post();
    }

    public function userInfo(int $userid){

        $this->userTable->getInfo($userid);
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

    }

    public function getAdmin(int $userid){
        $this->userTable->getInfo($userid);
        if($this->userTable->role != "administrator"){
            echo 'Not access';
            exit();
        }
    }

    public function editUser(int $userid, string $firstname, string $lastname, string $address, string $city, string $region, string $country){
        if($this->csrf) {
            if(!empty($firstname) AND !empty($lastname) AND !empty($address) AND !empty($city)  AND !empty($region)  AND !empty($country)){
                if(strlen($address) < 50) {
                    if(strlen($city) < 50) {
                        if(strlen($region) < 50) {
                            if(strlen($country) < 50) {
                                $this->userTable->editUserTable($userid, $firstname, $lastname, $address, $city, $region, $country);
                                $this->router->redirect('/account');
                            } else {
                                $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Votre pays ne doit pas dépasser 50 caractères !', 'danger');
                            }
                        } else {
                            $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Votre pays ne doit pas dépasser 50 caractères !', 'danger');
                        }
                    } else {
                        $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Votre pays ne doit pas dépasser 50 caractères !', 'danger');
                    }
                } else {
                    $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Votre pays ne doit pas dépasser 50 caractères !', 'danger');
                }
            } else {
                $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Vous devez valider tous le formulaire !', 'danger');
            }
        } else {
            $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Votre jeton CSRF est invalide !', 'danger');
        }
    }

    public function resetPassword(int $userid, string $newPassword, string $renewPassword){
        if($this->csrf) {
            if(!empty($newPassword)){
                if(strlen($renewPassword) >= 8) {
                    $this->userTable->updatePasswordTable($userid, $newPassword);
                    $this->router->redirect('/logout');
                } else {
                    $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Votre mot de passe doit être au minimum de 8 caractères.', 'danger');
                }
            } else {
                $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Vous devez valider tous le formulaire.', 'danger');
            }
        } else {
            $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Votre jeton CSRF est invalide.', 'danger');
        }
    }

    public function updateUserSolde(int $userid, string $afterSolde){
        $this->userTable->updateUserSoldeTable($userid, $afterSolde);
    }
}