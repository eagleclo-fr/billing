<?php
namespace src\Billing;

use src\Database\Database;
use src\Billing\Invoices;
use src\Billing\Database\PaymentTable;
use src\Billing\Database\RenewTable;
use src\Router\RouterHelper;
use src\Helper\FlashService;
use src\User\User;

class Renew
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->router = new RouterHelper();
        $this->flash = new FlashService();
        $this->invoices = new Invoices();
        $this->paymentTable = new PaymentTable();
        $this->renewTable = new RenewTable();
        $this->user = new User();
    }

    public function getService(int $userid, string $id_service){
        $this->renewTable->getServiceTable($id_service);

        if($userid == $this->renewTable->userid){
            $this->userid = $this->renewTable->userid;
            $this->id_service = $this->renewTable->id_service;
            $this->offer = $this->renewTable->offer;
            $this->status = $this->renewTable->status;
            $this->firstpaymentamount = $this->renewTable->firstpaymentamount;
            $this->price = $this->renewTable->price;
            $this->expiry = $this->renewTable->expiry;
            $this->name = $this->renewTable->name;
            $this->date_created = $this->renewTable->date_created;
            $this->date_updated = $this->renewTable->date_updated;
        } else {
            echo 'Erreur de synchronisation';
            exit();
        }

    }

    public function renewService($userid, $id_service, $month, string $date, string $price){
        $this->time = strtotime($date);
        $this->afterDate = date("Y-m-d", strtotime("+$month month", $this->time));
        $this->user->userInfo($userid);

        if($this->user->solde >= $price) {
            $this->afterSolde = ($this->user->solde - $price);

            $this->user->updateUserSolde($userid, $this->afterSolde);
            $this->renewTable->updateServiceTable($id_service, $this->afterDate);
            $this->date_paid = date('Y-m-d H:i:s');
            $this->product = 'Renouvellement Cloud (' . $id_service . ')';
            $this->invoices->createInvoiceCloud($userid, $price, $this->product, $this->date_paid);
            header('location: /cloud');

        } else {
            $this->flash->setFlash('<i class="bi bi-x-octagon-fill"></i> Vous n\'avez pas assez de solde sur votre compte.', 'danger');
        }
    }

}