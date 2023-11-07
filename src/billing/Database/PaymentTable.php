<?php
namespace src\Billing\Database;

use src\Database\Database;

class PaymentTable
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
    }

    public function createPaymentTable(int $userid, string $price, string $gateway, string $id_payment)
    {

        $this->payment = array('status' => 'pending', 'mode' => ''.$gateway.'');
        $this->date = array('date_creation' => ''.date('Y-m-d H:i:s').'', 'date_update' => ''.date('Y-m-d H:i:s').'');


        $this->insert = $this->pdo->prepare('INSERT INTO `billing_transactions` SET userid = :userid, uuid = :uuid, price = :price, payment = :payment, `date` = :date');
        $this->insert->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->insert->bindValue(':uuid', $id_payment, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':price', $price, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':payment', json_encode($this->payment), $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date', json_encode($this->date), $this->pdo::PARAM_STR);
        $this->insert->execute();
    }

    public function getPayment(string $uuid){
        $this->get = $this->pdo->prepare('SELECT * FROM `billing_transactions` WHERE uuid = :uuid');
        $this->get->bindValue(':uuid', $uuid, $this->pdo::PARAM_STR);
        $this->get->execute();
        $this->result = $this->get->fetch();
        $this->checkExist = $this->get->rowCount();

        if ($this->checkExist == 1) {

            $this->id = $this->result['id'];
            $this->userid = $this->result['userid'];
            $this->uuid = $this->result['uuid'];
            $this->price = $this->result['price'];

            $this->paymentJson = json_decode($this->result['payment']);
            $this->status = $this->paymentJson->status;
            $this->mode = $this->paymentJson->mode;

            $this->dateJson = json_decode($this->result['date']);
            $this->date_created = $this->dateJson->date_creation;

        } else {
            header('location: /billing/topup');
        }

    }

    public function updatePaymentTable(string $uuid, string $status, string $gateway, string $date_creation){

        $this->payment = array('status' => $status, 'mode' => ''.$gateway.'');
        $this->date = array('date_creation' => $date_creation, 'date_update' => ''.date('Y-m-d H:i:s').'');

        $this->update = $this->pdo->prepare("UPDATE `billing_transactions` SET `payment` = :payment, `date` = :date WHERE `uuid` = :uuid");
        $this->update->bindValue(':payment', json_encode($this->payment), $this->pdo::PARAM_STR);
        $this->update->bindValue(':date', json_encode($this->date), $this->pdo::PARAM_STR);
        $this->update->bindValue(':uuid', $uuid, $this->pdo::PARAM_STR);
        $this->update->execute();

    }

    public function addTransactionPayPalTable(int $userid, string $uuid, string $transaction, string $id_payment, string $shipping_address, string $paidAmount, string $currency, string $transaction_state)
    {
        $this->insert = $this->pdo->prepare('INSERT INTO `billing_module_paypal` SET userid = :userid, uuid = :uuid, txnid = :txnid, id_payment = :id_payment, shipping_address = :shipping_address, price = :price, currencycode = :currencycode, status = :status, `date` = :date');
        $this->insert->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->insert->bindValue(':uuid', $id_payment, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':txnid', $transaction, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':id_payment', $id_payment, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':shipping_address', $shipping_address, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':price', $paidAmount, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':currencycode', $currency, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':status', $transaction_state, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date', date('Y-m-d H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->execute();
    }

}
