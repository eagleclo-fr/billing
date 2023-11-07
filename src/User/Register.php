<?php
namespace src\User;

use src\Helper\IP;

use src\App;
use src\Database\Database;
use src\User\Database\RegisterTable;
use src\User\Entity\UserEntity;

use src\Helper\CSRF;
use src\Helper\FlashService;


class Register {

    public function __construct(){
        $this->database = New Database();
        $this->pdo = $this->database->connect();
        $this->registerTable = new RegisterTable();
        $this->flash = New FlashService();
        $this->csrf = csrf::post();
        $this->userEntity = new UserEntity();
    }

    public function createUser(string $mail, string $password, string $passwordConfirm, string $passwordDecoded){

        if($this->csrf) {
            if(!empty($mail) AND !empty($password) AND !empty($passwordConfirm)) {
                if(strlen($passwordDecoded) >= 8){
                    if(filter_var($this->userEntity->getMail($mail), FILTER_VALIDATE_EMAIL)) {
                        $this->registerTable->checkUserExist($this->userEntity->getMail($mail));
                        if($this->registerTable->checkMail == 0) {
                            if($this->userEntity->getPassword($password) == $this->userEntity->getConfirmPassword($passwordConfirm)) {

                                $this->key = $this->userEntity->getKeyUser();
                                $this->id_customer = $this->userEntity->IDCustomer();
                                $this->ip = IP::get();

                                $this->registerTable->createUserTable($this->id_customer, $this->userEntity->getMail($mail), $this->userEntity->getPassword($password), $this->key, $this->ip);
                                //$this->notification->getSend($this->id_customer, $this->userEntity->getMail($mail), $this->key);

                                $this->flash->setFlash('Inscription réussi !', 'success');

                            } else {
                                $this->flash->setFlash('Les mots de passes ne correspondent pas.', 'danger');
                            }
                        } else{
                            $this->flash->setFlash('Votre adresse Mail est déjà utilisé par un compte !', 'danger');
                        }
                    } else {
                        $this->flash->setFlash('Votre adresse mail n\'est pas valide.', 'danger');
                    }
                }else{
                    $this->flash->setFlash('Le mot de passe doit avoir minimum 8 caractères !', 'danger');
                }
            } else {
                $this->flash->setFlash('Tous les champs doivent être complétés !', 'danger');
            }
        } else {
            $this->flash->setFlash('Votre jeton CSRF est invalide !', 'danger');
        }
    }
}


?>