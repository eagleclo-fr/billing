<?php

namespace src\Billing;

use src\Router\RouterHelper;
use src\Database\Database;
use src\Billing\Database\BillingCardTable;
use src\Helper\FlashService;
use src\Billing\Payment;

class Card
{

    /**
     * Card constructor
     */

    public function __construct()
    {
        $this->database = new Database();
        $this->pdo = $this->database->connect();
        $this->redirect = new RouterHelper();
        $this->BillingCardTable = new BillingCardTable();
        $this->flash = new FlashService();
        $this->payment = new Payment();
    }

    /**
     * @param int $userid
     * @return void
     */

    public function checkAccountStripe(int $userid)
    {
        $this->BillingCardTable->checkAccountStripeTable($userid);
        if($this->BillingCardTable->checkExist == 1){
            $this->response = true;
        } else {
            $this->response = false;
        }
    }

    /**
     * @param int $userid
     * @param string $mail
     * @return void
     */

    public function createAccount(int $userid, string $mail){
        $stripe = new \Stripe\StripeClient('sk_live_111111111111111111111111111111111111111111111111111111111111');
        $this->endPoint = $stripe->customers->create(['email' => $mail,]);
        $this->jsonDecoded = json_encode($this->endPoint);
        $this->resultPoint = json_decode($this->jsonDecoded, true);
        $this->id_account_stripe = $this->resultPoint['id'];
        $this->BillingCardTable->createAccountTable($userid, $mail, $this->id_account_stripe);
    }

    /**
     * @param int $userid
     * @param string $mail
     * @return void
     */

    public function LinkCC(int $userid, string $mail)
    {
        $this->token = $_POST['stripeToken'];
        $this->checkAccountStripe($userid);
        if ($this->token != null) {
            if ($this->response == true) {
                $this->BillingCardTable->getAccountStripeTable($userid);
                $this->addCard($userid, $this->BillingCardTable->id_account, $this->token, 'true');
                $this->redirect->redirect('/manager/billing/payment/method/');
            } else {
                $this->createAccount($userid, $mail);
                $this->addCard($userid, $this->id_account_stripe, $this->token, 'true');
                $this->redirect->redirect('/manager/billing/payment/method/');
            }
        } else {
            $this->flash->setFlash('Erreur interne !', 'danger');
        }

    }

    /**
     * @param int $userid
     * @param string $id_account_stripe
     * @param string $token
     * @return void
     */

    public function addCard(int $userid, string $id_account_stripe, string $token, string $add)
    {
        $stripe = new \Stripe\StripeClient('sk_live_111111111111111111111111111111111111111111111111111111111111');
        $source = $stripe->customers->createSource('' . $id_account_stripe . '', ['source' => $token]);
        $this->response_json_cc = json_encode($source);
        $this->response_decoded_json_cc = json_decode($this->response_json_cc, true);
        $this->expiry = '31/' . $this->response_decoded_json_cc['exp_month'] . '/' . $this->response_decoded_json_cc['exp_year'];
        $this->account = 'XXXXXXXXXXXX' . $this->response_decoded_json_cc['last4'] . '';
    }

    /**
     * @param $user_id
     * @return void
     */

    public function unLinkCC(int $userid, string $id_account, string $id_cc)
    {
        $stripe = new \Stripe\StripeClient('sk_live_111111111111111111111111111111111111111111111111111111111111');
        $stripe->customers->deleteSource('' . $id_account . '', '' . $id_cc. '', []);
    }

    /**
     * @param $userid
     * @param $montant
     * @return void
     */

    public function chargeAccountCC(int $userid, string $price, $id_account_stripe)
    {
        $stripe = new \Stripe\StripeClient('sk_live_111111111111111111111111111111111111111111111111111111111111');
        $this->charge_cb = $stripe->charges->create(['amount' => $price * 100, 'currency' => 'eur', 'customer' => '' . $id_account_stripe . '',]);
        $response_json_encoded = json_encode($this->charge_cb);
        $response_decoded = json_decode($response_json_encoded, true);
        $this->status_payment = $response_decoded['status'];
        $this->id_transaction = $response_decoded['id'];
    }

    public function Payment(int $userid, string $mail, string $price, string $uuid)
    {
        $this->token = $_POST['stripeToken'];
        $this->checkAccountStripe($userid);
        if ($this->token != null) {
            if ($this->response == true) {
            } else {
                $this->createAccount($userid, $mail);
            }
            $this->BillingCardTable->getAccountStripeTable($userid);

            try {
                $this->addCard($userid, $this->BillingCardTable->id_account, $this->token, 'false');
                $this->chargeAccountCC($userid, $price, $this->BillingCardTable->id_account);

                if($this->status_payment == "succeeded") {

                    $this->BillingCardTable->addPaymentTable($userid, $uuid, $price, $this->id_transaction, $this->status_payment);
                    $this->payment->updatePayment($uuid, "stripe");
                }

            } catch (\Stripe\Exception\RateLimitException $e) {
                header('location: /billing/topup/success/'.$uuid.'');
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                header('location: /billing/topup/success/'.$uuid.'');
            } catch (\Stripe\Exception\AuthenticationException $e) {
                header('location: /billing/topup/success/'.$uuid.'');
            } catch (\Stripe\Exception\ApiConnectionException $e) {
                header('location: /billing/topup/success/'.$uuid.'');
            } catch (\Stripe\Exception\ApiErrorException $e) {
                header('location: /billing/topup/success/'.$uuid.'');
            } catch (Exception $e) {
                header('location: /billing/topup/success/'.$uuid.'');
            }
        } else {
            $this->flash->setFlash('Erreur interne !', 'danger');
        }

    }


}