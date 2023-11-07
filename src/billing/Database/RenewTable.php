<?php
namespace src\Billing\Database;

use src\Database\Database;

class RenewTable
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
    }

    public function getServiceTable(string $id_service)
    {
        $this->get = $this->pdo->prepare('SELECT * FROM `cloud` WHERE idservice = :id_service');
        $this->get->bindValue(':id_service', $id_service, $this->pdo::PARAM_STR);
        $this->get->execute();
        $this->result = $this->get->fetch();
        $this->checkExist = $this->get->rowCount();

        if ($this->checkExist == 1) {
            $this->userid = $this->result['userid'];
            $this->id_service = $this->result['idservice'];
            $this->offer = $this->result['offer'];
            $this->status = $this->result['status'];
            $this->firstpaymentamount = $this->result['firstpaymentamount'];
            $this->price = $this->result['price'];
            $this->expiry = $this->result['expiry'];
            $this->name = $this->result['name'];
            $this->date_created = $this->result['date_created'];
            $this->date_updated = $this->result['date_updated'];
        } else {
            echo 'Erreur de synchronisation';
            exit();
        }
    }

    public function updateServiceTable(string $id_service, string $newDate){
        $this->update = $this->pdo->prepare("UPDATE `cloud` SET expiry = :expiry WHERE `idservice` = :idservice");
        $this->update->bindValue(':expiry', $newDate, $this->pdo::PARAM_STR);
        $this->update->bindValue(':idservice', $id_service, $this->pdo::PARAM_STR);
        $this->update->execute();
    }
}