<?php
namespace src\Billing;

use src\Database\Database;
use src\Billing\Database\InvoicesTable;
use src\Billing\Database\PaymentTable;
use src\Router\RouterHelper;
use src\Helper\FlashService;
use src\User\User;

class Payment
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->router = new RouterHelper();
        $this->flash = new FlashService();
        $this->invoicesTable = new InvoicesTable();
        $this->paymentTable = new PaymentTable();
        $this->user = new User();
    }

    public function GenerateUUID($longueur = 10)
    {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $longueurMax = strlen($caracteres);
        $this->chaineAleatoire = '';
        for ($i = 0; $i < $longueur; $i++)
        {
            $this->chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
        }
        return $this->chaineAleatoire;
    }


    public function createPayment(int $userid, string $price, string $gateway)
    {
        $this->amountNumeric = str_replace(',', '.', $price);
        if(is_numeric($this->amountNumeric)){
            if($gateway == "paypal"){
                $this->gateway = "paypal";
            } else if($gateway == "stripe"){
                $this->gateway = "stripe";
            } else {
                $this->gateway = "paypal";
            }
            if($price >= "1") {
                $this->id_payment = $this->GenerateUUID();
                $this->paymentTable->createPaymentTable($userid, $this->amountNumeric, $this->gateway, $this->id_payment);
                header('location: /billing/topup/' . $this->gateway . '/' . $this->id_payment . '');
            } else {
                $this->flash->setFlash('Le minimum est de 1€ !', 'danger');
            }
        } else {
        $this->flash->setFlash('Cette valeur est impossible !', 'danger');
    }
}

    public function getPayment(int $userid, string $gateway, string $uuid){
        $this->paymentTable->getPayment($uuid);
        $this->id = $this->paymentTable->id;
        $this->uuid = $this->paymentTable->uuid;
        $this->price = $this->paymentTable->price;
        $this->status = $this->paymentTable->status;
        $this->mode = $this->paymentTable->mode;

    }

    public function test(){

        echo 'cc';
    }

    public function updatePayment($uuid, $mode)
    {
        $this->paymentTable->getPayment($uuid);

        $this->id = $this->paymentTable->id;
        $this->userid = $this->paymentTable->userid;
        $this->uuid = $this->paymentTable->uuid;
        $this->price = $this->paymentTable->price;
        $this->status = $this->paymentTable->status;
        $this->mode = $this->paymentTable->mode;
        $this->date_created = $this->paymentTable->date_created;

        if ($this->mode == $mode) {
            $this->user->userInfo($this->userid);
            $this->afterSolde = ($this->user->solde + $this->price);
            $this->user->updateUserSolde($this->userid, $this->afterSolde);
            $this->paymentTable->updatePaymentTable($uuid, 'success', $mode, $this->date_created);
            $this->product = 'Achat Solde (' . $this->price . '€)';
            $this->date = date('d-m-Y H:i:s');
            $this->invoicesTable->createInvoiceTable($this->userid, $this->price, $this->product, $this->date);
            header('location: /billing/topup/success/' . $uuid);
        } else {
            header('location: /billing/topup');
        }
    }

    public function addTransactionPayPal(int $userid, string $uuid, string $transaction, string $id_payment, string $shipping_address, string $paidAmount, string $currency, string $transaction_state){
        $this->paymentTable->addTransactionPayPalTable($userid, $uuid, $transaction, $id_payment, $shipping_address, $paidAmount, $currency, $transaction_state);
    }
}