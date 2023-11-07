<?php

use src\User\Session;
use src\User\User;
use src\Billing\Payment;
use src\Billing\PayPal;

$session = new Session();
$user = new User();
$payment = new Payment();

$session->getSession();
$user->userInfo($session->userid);

if(!empty($params['paymentid']) && !empty($params['token']) && !empty($params['payerid'] && !empty($params['uuid']))){
    $paypal = new PayPal();

    // Get payment info from URL
    $paymentid = $params['paymentid'];
    $token = $params['token'];
    $payerID = $params['payerid'];
    $uuid = $params['uuid'];
    $paymentCheckAPI = $paypal->validate($paymentid, $payerID, $token, $uuid);

    $paymentCheck = $payment->pdo->prepare('SELECT * FROM billing_module_paypal WHERE id_payment = ?');
    $paymentCheck->execute(array($paymentid));
    $paymentInfo = $paymentCheck->fetch();
    $paymentExist = $paymentCheck->rowCount();

    $searchpayout = $payment->pdo->prepare('SELECT * FROM billing_transactions WHERE uuid = ?');
    $searchpayout->execute(array($uuid));
    $getInfo = $searchpayout->fetch();
    $paymentJson = json_decode($getInfo['payment']);


    // If the payment is valid and approved
    if($paymentCheck) {

        if(!(empty($paymentCheckAPI->id))){

            if($paymentExist == 0){

                if($getInfo['price'] == $paymentCheckAPI->transactions[0]->amount->details->subtotal){

                    $id_payment = $paymentCheckAPI->id;
                    $status = $paymentCheckAPI->state;
                    $payerFirstName = $paymentCheckAPI->payer->payer_info->first_name;
                    $payerLastName = $paymentCheckAPI->payer->payer_info->last_name;
                    $payerEmail = $paymentCheckAPI->payer->payer_info->email;
                    $payerID = $paymentCheckAPI->payer->payer_info->payer_id;

                    $adresse = $paymentCheckAPI->payer->payer_info->shipping_address->line1;
                    $city = $paymentCheckAPI->payer->payer_info->shipping_address->city;
                    $postal_code = $paymentCheckAPI->payer->payer_info->shipping_address->postal_code;
                    $pays = $paymentCheckAPI->payer->payer_info->shipping_address->country_code;

                    $paidAmount = $paymentCheckAPI->transactions[0]->amount->details->subtotal;
                    $currency = $paymentCheckAPI->transactions[0]->amount->currency;

                    $transaction = $paymentCheckAPI->transactions[0]->related_resources[0]->sale->id;
                    $transaction_state = $paymentCheckAPI->transactions[0]->related_resources[0]->sale->state;

                    $shipping_address = array('payerfirstname' => ''.$payerFirstName.'', 'payerlastname' => ''.$payerLastName.'', 'payeremail' => ''.$payerEmail.'', 'payerid' => ''.$payerID.'', 'address' => ''.$adresse.'', 'city' => ''.$city.'', 'postal_code' => ''.$postal_code.'', 'country' => ''.$pays.'');
                    $payment->addTransactionPayPal($session->userid, $uuid, $transaction, $id_payment, json_encode($shipping_address), $paidAmount, $currency, $transaction_state);
                    $payment->updatePayment($uuid, 'paypal');

                } else {
                    echo 'not found price';
                }
            } else {
                echo 'exit deja';
            }
        } else {
            echo 'not';
        }

    }

}else{
    header('location: /manager/billing/history/');

}
?>