<?php
namespace src\Support\Database;

use src\Database\Database;
use src\Support\Entity\Ticket;

class SupportTable
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->entity = new Ticket();
    }

    public function createTableTicket(int $userid, string $department, string $title) {
        $this->insert = $this->pdo->prepare('INSERT INTO `support` SET userid = :userid, author = :author, content = :content, department = :department, status = :status, date_created = :date_created, date_updated = :date_updated');
        $this->insert->bindValue(':userid', $userid, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':author', "guest", $this->pdo::PARAM_STR);
        $this->insert->bindValue(':content', $title, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':department', $department, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':status', "OPEN", $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_created', date('d-m-Y H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_updated', date('d-m-Y H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->execute();
        $this->idTicket = $this->pdo->lastInsertId();
    }

    public function createMessage(int $id, int $userid, string $messageReply) {
        $this->insert = $this->pdo->prepare('INSERT INTO `support_reply` SET idticket = :idticket, userid = :userid, author = :author, message = :message, date_created = :date_created');
        $this->insert->bindValue(':idticket', $id, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':userid', $userid, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':author', "Vous", $this->pdo::PARAM_STR);
        $this->insert->bindValue(':message', $messageReply, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_created', date('d-m-Y H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->execute();
    }

    public function updateSupport(int $id, string $state)
    {
        $this->update = $this->pdo->prepare('UPDATE `support` SET status = :status WHERE id = :id');
        $this->update->bindValue(':id', $id);
        $this->update->bindValue(':status', $state);
        $this->update->execute();
    }


    public function closeTicketTable(int $id){
        $this->update = $this->pdo->prepare('UPDATE `support` SET status = :status WHERE id = :id');
        $this->update->bindValue(':id', $id);
        $this->update->bindValue(':status', 'CLOSE');
        $this->update->execute();

    }

   
    public function getAllTicketsTable()
    {
        $this->select = $this->pdo->prepare('SELECT * FROM `support`');
        $this->select->bindValue('$this->pdo::PARAM_INT');
        $this->select->execute();
        $this->exist = $this->select->rowCount();
        while ($this->result = $this->select->fetch(\PDO::FETCH_ASSOC)) {

            $this->entity->getId($this->result['id']);
            $this->entity->getDepartment($this->result['department']);
            $this->date_updated = $this->entity->getDate($this->result['date_updated']);
            $this->date_created = $this->entity->getDate($this->result['date_created']);
            $this->entity->getContent($this->result['content']);
            $this->entity->getState($this->result['status']);

            echo '<tr>
                <td>CT-B-'.$this->entity->id.'</td>
                <td>'.$this->entity->content.'</td>
                <td>'.$this->entity->department.'</td>
                <td>'.$this->date_created.'</td>
                <td>'.$this->date_updated.'</td>
                <td>'.$this->entity->state.'</td>
                <td class="text-center"><a href="/support/'.$this->entity->id.'" class="btn btn-primary btn-sm">Accéder</a>
                </td>
            </tr>';
        }

        if ($this->exist <= 0) {
            echo '<tr>
                <td class="text-center" colspan="7"><strong>Aucun enregistrement trouvé</strong></td>
            </tr>';
        } else {
        }
    }
    public function getTicketTable(int $id){
        $this->get = $this->pdo->prepare('SELECT * FROM `support` WHERE id = :id');
        $this->get->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $this->get->execute();
        $this->result = $this->get->fetch();
        $this->checkExist = $this->get->rowCount();

        if ($this->checkExist == 1) {

            $this->id = $this->result['id'];
            $this->userid = $this->result['userid'];
            $this->author = $this->result['author'];
            $this->content = $this->result['content'];
            $this->department = $this->result['department'];
            $this->status = $this->result['status'];
            $this->date_created = $this->result['date_created'];
            $this->date_updated = $this->result['date_updated'];
        }
    }

    public function getAllReplyTickets(int $id)
    {
        $this->select = $this->pdo->prepare('SELECT * FROM `support_reply` WHERE `idticket` = :idticket ORDER by id DESC');
        $this->select->bindValue(':idticket', $id, $this->pdo::PARAM_INT);
        $this->select->execute();
        $this->exist = $this->select->rowCount();
        while ($this->result = $this->select->fetch(\PDO::FETCH_ASSOC)) {

            $this->author = $this->result['author'];
            $this->message = nl2br($this->result['message']);
            $this->date_created = date('d/m/Y à H:i', strtotime($this->result['date_created']));

            if($this->author == "Administrator"){
                $this->author = '<font color="red">Équipe CENTERCLOUD</font>';
            }

            echo ' <div class="col-md-12">
                       <div class="card border-0 shadow">
                           <div class="card-body">
                               <div class="mb-3">
                               <br>
                               <span class="h6 fw-bold">De '.$this->author.'</span>
                               </div>
                               <p>'.$this->message.'</p>
                               <p class="text-left"><strong>Date de réception </strong>'.$this->date_created.'</p>
                           </div>
                       </div>
                   </div>';
                }
            }

}