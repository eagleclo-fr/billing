<?php
namespace src\User;

use src\Database\Database;
use src\Router\RouterHelper;
use src\Helper\FlashService;
use src\Helper\CSRF;
use src\User\Session;
use src\User\Database\LoginTable;
use src\User\Entity\UserEntity;

class Login
{

    public function __construct()
    {
        $this->router = new RouterHelper();
        $this->flash = new FlashService();
        $this->database = New Database();
        $this->pdo = $this->database->connect();
        $this->csrf = csrf::post();
        $this->session = new Session();
        $this->loginTable = new LoginTable();
        $this->userEntity = new UserEntity();
    }


    public function connectUser(string $mail, string $password)
    {
        if($this->csrf) {
            if (!empty($this->userEntity->getMail($mail)) AND !empty($this->userEntity->getPassword($password))) {
                $this->loginTable->checkLogin($this->userEntity->getMail($mail), $this->userEntity->getPassword($password));
                if($this->loginTable->checkExist == 1){
                    if($this->loginTable->banned == '0') {
                        $this->session->addSession($this->loginTable->userid, 'open');
                        $this->router->redirect('/manager');
                    } else {
                        $this->flash->setFlash('Nous n\'avons pas été en mesure de connecter votre compte, merci de nous contacter à contact@centercloud.fr', 'danger');
                    }
                } else {
                    $this->flash->setFlash('Vos identifiants/mot de passe sont invalide !', 'danger');
                }
            } else {
                $this->flash->setFlash('Il manque des champs !', 'danger');
            }
        } else {
            $this->flash->setFlash('Votre jeton CSRF est invalide !', 'danger');
        }
    }
}
