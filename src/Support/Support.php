<?php
namespace src\Support;

use src\Database\Database;
use src\User\User;
use src\Support\Database\SupportTable;
use src\Helper\FlashService;

class Support
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->user = new User();
        $this->supportTable = new SupportTable();
        $this->flash = new FlashService();
    }

    public function getAllTickets(int $userid)
    {
        return $this->supportTable->getAllTicketsTable($userid);
    }
   

    public function getAllReplyTickets(int $id)
    {
        return $this->supportTable->getAllReplyTickets($id);
    }

    public function closeTicket(int $id){
        $this->supportTable->closeTicketTable($id);
        header('location: /support');
    }

    public function replyTicket(int $id, int $userid, string $message){
        if(!empty($message)){
            $this->supportTable->createMessage($id, $userid, $message);
            $this->supportTable->updateSupport($id, 'OPEN');
            header('location: /support/'.$id);
        }else{
            $this->flash->setFlash('Veuillez saisir tout les champs !', 'danger');
        }

    }

    public function addTicket(string $department, string $title, string $message, int $userid, string $mail){
        if(!empty($department) AND !empty($title) AND !empty($message)){
            if(strlen($title) < 32) {
                $this->supportTable->createTableTicket($userid, $department, $title);
                $this->supportTable->createMessage($this->supportTable->idTicket, $userid, $message);
                $this->flash->setFlash('Votre demande d\'assistance est créer !', 'success');
                echo '<meta http-equiv="refresh" content="2;URL=/support/'.$this->supportTable->idTicket.'">';
            } else {
                $this->flash->setFlash('Vous avez trop mis de caractères sur votre sujet du ticket !', 'danger');
            }
        } else {
            $this->flash->setFlash('Il manque des champs !', 'danger');
        }
    }

    public function getTicket(int $userid, int $id){

        $this->supportTable->getTicketTable($id);
        if($userid == $this->supportTable->userid){

            $this->id = $this->supportTable->id;
            $this->userid = $this->supportTable->userid;
            $this->author = $this->supportTable->author;
            $this->content = $this->supportTable->content;
            $this->department = $this->supportTable->department;
            $this->status = $this->supportTable->status;
            $this->date_created = $this->supportTable->date_created;
            $this->date_updated = $this->supportTable->date_updated;

        } else {
            header('location: /support');
        }

    }

}