<?php
namespace src\Billing;

use src\Database\Database;
use src\Billing\Database\OfferTable;
use src\Billing\Database\InvoicesTable;
use src\Router\RouterHelper;
use src\Helper\FlashService;

class Invoices
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->router = new RouterHelper();
        $this->offerTable = new OfferTable();
        $this->flash = new FlashService();
        $this->invoicesTable = new InvoicesTable();
    }

    public function createInvoiceCloud(int $userid, string $price, string $product, string $date_paid){
        $this->invoicesTable->createInvoiceTable($userid, $price, $product, $date_paid);
    }

    public function getAllInvoices(int $userid)
    {
        return $this->invoicesTable->getAllInvoices($userid);
    }

    public function getAllInvoicesLimit(int $userid, int $limit)
    {
        return $this->invoicesTable->getAllInvoicesLimit($userid, $limit);
    }

    public function getInvoice(int $userid, string $id_invoice){

        $this->invoicesTable->getInvoiceTable($id_invoice);
        if($userid == $this->invoicesTable->userid){

            $this->id = $this->invoicesTable->id;
            $this->product = $this->invoicesTable->product;
            $this->price = $this->invoicesTable->price;
            $this->date_created = $this->invoicesTable->date_created;
            $this->date_paid = $this->invoicesTable->date_paid;

        } else {
            header('location: /billing/invoices');
        }

    }


}