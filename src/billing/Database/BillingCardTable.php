<?php
namespace src\Billing\Database;

use src\Database\Database;

class BillingCardTable
{

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
    }

    public function checkAccountStripeTable(int $userid){
        $this->select = $this->pdo->prepare("SELECT * FROM `billing_method_payment_stripe_account` WHERE userid = :userid");
        $this->select->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->select->execute();
        $this->checkExist = $this->select->rowCount();
    }

    public function getAccountStripeTable(int $userid){
        $this->select = $this->pdo->prepare("SELECT * FROM `billing_method_payment_stripe_account` WHERE userid = :userid");
        $this->select->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->select->execute();
        $this->result = $this->select->fetch();

        $this->id_account = $this->result['id_account'];

    }

    public function getMethodStripe(int $id){
        $this->select = $this->pdo->prepare("SELECT * FROM `billing_method_payment_stripe` WHERE id = :id");
        $this->select->bindValue(':id', $id, $this->pdo::PARAM_INT);
        $this->select->execute();
        $this->result = $this->select->fetch();

        $this->id_account = $this->result['id_account'];
        $this->id_cc = $this->result['id_cc'];
    }


    public function addCardTable(int $userid, string $id_account_stripe, string $id_cc, string $number_cc, string $brand_cc, string $exp_month, string $exp_year)
    {
        $this->insert = $this->pdo->prepare('INSERT INTO `billing_method_payment_stripe` SET userid = :userid, `id_account` = :id_account, `id_cc` = :id_cc, `number_cc` = :number_cc, `brand_cc` = :brand_cc, `exp_month_cc` = :exp_month_cc, `exp_year_cc` = :exp_year_cc, date_created = :date_created');
        $this->insert->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->insert->bindValue(':id_account', $id_account_stripe, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':id_cc', $id_cc, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':number_cc', $number_cc, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':brand_cc', $brand_cc, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':exp_month_cc', $exp_month, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':exp_year_cc', $exp_year, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_created', date('Y-m-d H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->execute();
        $this->idInsert = $this->pdo->lastInsertId();
    }

    public function createAccountTable(int $userid, string $mail, string $id_account_stripe)
    {
        $this->insert = $this->pdo->prepare('INSERT INTO `billing_method_payment_stripe_account` SET userid = :userid, `id_account` = :id_account, date_created = :date_created');
        $this->insert->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->insert->bindValue(':id_account', $id_account_stripe, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date_created', date('Y-m-d H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->execute();
    }

    public function addPaymentTable(int $userid, string $uuid, string $price, string $id_transaction, string $status_payment)
    {
        $this->insert = $this->pdo->prepare('INSERT INTO `billing_module_stripe` SET userid = :userid, `uuid` = :uuid, price = :price, txn_id = :txn_id, status = :status, date = :date');
        $this->insert->bindValue(':userid', $userid, $this->pdo::PARAM_INT);
        $this->insert->bindValue(':uuid', $uuid, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':price', $price, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':txn_id', $id_transaction, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':status', $status_payment, $this->pdo::PARAM_STR);
        $this->insert->bindValue(':date', date('Y-m-d H:i:s'), $this->pdo::PARAM_STR);
        $this->insert->execute();
    }


}