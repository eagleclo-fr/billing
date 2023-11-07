<?php

namespace src\User;

use src\User\Database\UserTable;
use src\Router\RouterHelper;
use src\Helper\IP;
use src\Helper\FlashService;
use src\User\Database\LoginTable;
use src\Database\Database;

class Session {

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->userTable = new UserTable();
        $this->router = new RouterHelper();
        $this->flash = new FlashService();
        $this->loginTable = new LoginTable();
    }

    public function addSession(int $userid, string $db){

        $this->gen = "abcdefghijklmnopqrstuvwxyz123456789abcdefghijklmnopqrstuvwxyz123456789";
        $this->gen = str_shuffle($this->gen);
        $this->sid = substr($this->gen,0,20);
        $this->update = date('Y-m-d H:i:s');
        $ipagent = IP::get();
        $this->exploseIP = explode(", ", $ipagent);


        $this->object = array('session' => ''.$this->sid.'', 'url' => '', 'status' => 'active', 'useragent' => ''.$_SERVER['HTTP_USER_AGENT'].'', 'ip' => ''.$this->exploseIP[0].'');
        $this->data = json_encode($this->object);
        $insert = $this->pdo->prepare('INSERT INTO `sessions`(`userid`, `sid`, `update`,  `status`, `data`) VALUES (?,?,?,?,?)');
        $insert->execute(array($userid, $this->sid, $this->update, $db, $this->data));
        $_SESSION['sid'] = $this->sid;
    }

    public function getCheckSession(){
        if(!(empty($_SESSION['sid']))) {
            $this->req = $this->pdo->prepare('SELECT * FROM sessions WHERE sid = ?');
            $this->req->execute(array($_SESSION['sid']));
            $this->info = $this->req->fetch();
            $this->exist = $this->req->rowCount();
            if($this->exist == 1){
                $this->userid = $this->info['userid'];
            } else {
                $this->userid = 'null';
            }
        } else {
            $this->userid = 'null';
        }
    }

    public function getSession(){
        $sid = $_SESSION['sid'];

        if(!(empty($sid))) {

            $this->req = $this->pdo->prepare('SELECT * FROM sessions WHERE sid = ?');
            $this->req->execute(array($sid));
            $this->info = $this->req->fetch();
            $this->exist = $this->req->rowCount();
            $this->dataDecoded = json_decode($this->info['data']);
            $this->userid = $this->info['userid'];
            $this->status = $this->info['status'];
            $this->sid = $this->info['sid'];
            $this->ip = IP::get();
            if($this->userid != null){
                $this->userTable->checkUserExist($this->userid);

                if ($this->userTable->checkExist == 1) {

                    if (!($this->exist == 0)) {
                        $ipagent = IP::get();
                        $this->exploseIP = explode(", ", $ipagent);
                        if ($this->dataDecoded->ip == $this->exploseIP[0]) {
                            $this->date = date('Y-m-d H:i:s', strtotime('+ 1 hour'));

                            if($this->status == "lock") {
                                if($_SERVER['REQUEST_URI'] != "/lock") {
                                    $this->router->redirect('/lock');
                                }
                            }

                            if ($this->info['update'] >= $this->date) {
                                $this->router->redirect('/logout');

                            } else {

                                $this->update = date('Y-m-d H:i:s');
                                $this->object = array('session' => '' . $sid . '', 'url' => '' . $_SERVER['REQUEST_URI'] . '', 'status' => 'active', 'useragent' => '' . $_SERVER['HTTP_USER_AGENT'] . '', 'ip' => '' . $this->exploseIP[0] . '');
                                $this->data = json_encode($this->object);

                                $update = $this->pdo->prepare('UPDATE `sessions` SET `userid` = ?, `sid` = ?, `update` = ?, `data` = ? WHERE `sid` = ?');
                                $update->execute(array($this->userid, $sid, $this->update, $this->data, $sid));
                            }
                        } else {
                            $this->router->redirect('/logout');
                        }
                    } else {
                        $this->router->redirect('/logout');
                    }
                } else {
                    $this->router->redirect('/logout');
                }
            } else {
                $this->router->redirect('/logout');
            }
        } else {
            $this->router->redirect('/logout');
        }
    }

    public function getActiveSession(){
        if(!(empty($_SESSION['sid']))) {
            $this->req = $this->pdo->prepare('SELECT * FROM sessions WHERE sid = ?');
            $this->req->execute(array($_SESSION['sid']));
            $this->info = $this->req->fetch();
            $this->exist = $this->req->rowCount();
            $this->date = date('Y-m-d H:i:s', strtotime('+ 1 hour'));

            if (($this->exist == 1)) {
                $this->router->redirect('/manager');
                if ($this->info['update'] >= $this->date) {
                    $this->router->redirect('/logout');
                }
            }
        }
    }

    public function terminateSession(){
        $sid = $_SESSION['sid'];

        if(!(empty($sid))) {

            $this->req = $this->pdo->prepare('SELECT * FROM sessions WHERE sid = ?');
            $this->req->execute(array($sid));
            $this->info = $this->req->fetch();
            $this->exist = $this->req->rowCount();
            $this->dataDecoded = json_decode($this->info['data']);
            $this->userid = $this->info['userid'];
            if($this->userid != null){
                $this->userTable->checkUserExist($this->userid);

                if ($this->userTable->checkExist == 1) {

                    $this->delete = $this->pdo->prepare('DELETE FROM `sessions` WHERE `sid` = :sid');
                    $this->delete->bindValue(':sid', $sid, $this->pdo::PARAM_STR);
                    $this->delete->execute();
                    $this->router->redirect('/login');

                } else {
                    $this->router->redirect('/login');
                }
            } else {
                $this->router->redirect('/login');
            }
        } else {
            $this->router->redirect('/login');
        }
    }

    public function killSession(int $id){
        $this->delete = $this->pdo->prepare('DELETE FROM `sessions` WHERE `id` = :id');
        $this->delete->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $this->delete->execute();
    }

    public function updateSession($sid){
        $this->update = $this->pdo->prepare('UPDATE `sessions` SET status = :status WHERE `sid` = :sid');
        $this->update->bindValue(':status', 'open', $this->pdo::PARAM_STR);
        $this->update->bindValue(':sid', $sid, $this->pdo::PARAM_STR);
        $this->update->execute();
    }

    public function unlock($sid, $key, $login_key){
        if(!empty($key)){
            if($key == $login_key){
                $this->updateSession($sid);
                $this->router->redirect('/manager/hub/');
            } else {
                $this->flash->setFlash('Votre code est incorrect !', 'danger');
            }
        } else {
            $this->flash->setFlash('Il manque des champs !', 'danger');
        }
    }


}
