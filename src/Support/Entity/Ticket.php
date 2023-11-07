<?php
namespace src\Support\Entity;

class Ticket
{

    public function getState($state)
    {
        if ($state == "OPEN") {
            $this->state = '<font color="green">OUVERT</font>';
            $this->stateText = '<font color="green"><i class="fa fa-circle" aria-hidden="true"></i></font> Ouvert';
        } elseif ($state == "REPLY") {
            $this->state = '<font color="orange">EN ATTENTE</font>';
            $this->stateText = '<font color="orange"><i class="fa fa-circle" aria-hidden="true"></i></font> Répondu';
        } elseif ($state == "CLOSE") {
            $this->state = '<font color="red">FERMÉ</font>';
            $this->stateText = '<font color="gray"><i class="fa fa-circle" aria-hidden="true"></i></font> Fermé';
        }
        return true;
    }

    public function getDepartment($department){
        $this->department = $department;
        return $this->department;
    }

    public function getMessage($message){
        $this->message = nl2br($message);
        return $this->message;
    }

    public function getService($service){
        $this->service = $service;
        return $this->service;
    }

    public function getDate($date){
        $this->date = $date;
        return $this->date;
    }

    public function getContent($content){
        $this->content = $content;
        return $this->content;
    }

    public function getId($id){
        $this->id = $id;
        return $this->id;
    }

    public function getUUID($uuid){
        $this->uuid = $uuid;
        return $this->uuid;
    }

    public function getUserID($userid){
        $this->userid = $userid;
        return $this->userid;
    }

}