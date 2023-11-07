<?php

use src\User\Session;
use src\User\User;
use src\Billing\Payment;
use src\Helper\CSRF;
use src\Helper\FlashService;
use src\Billing\PayPal;

$session = new Session();
$user = new User();
$paypal = new PayPal();

$session->getSession();
$user->userInfo($session->userid);
$payment = new Payment();

$id = $params['id'];
if(isset($id)) {
    $payment->getPayment($session->userid, 'paypal', $id);
}



?>
<?php require 'templates/layouts/header.php'; ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Facturation</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/manager">Dashboard</a></li>
                    <li class="breadcrumb-item active">Facturation</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-md-6">
                <br>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <br>
                        Montant: <strong><?= number_format($payment->price, 2, ',', ' ');?>€</strong><br>
                        Type de paiement: <strong>Unique</strong>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <br>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <br>
                        [<?= number_format($payment->price, 2, ',', ' ');?>€] - Restant à payer
                        <br>Module de paiement: <strong>GENIUSWEER V2.1</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <br>
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="col-md-12" style="margin-bottom: 20px;">
                        <div class="item">
                            <br>
                            <center><h5 style="color:#4FA5DD">Vous devez cliquer sur le bouton ci-dessous pour effectuer le paiement.</h5></center>
                            <center><div id="paypal-button"></div></center>
                            <script src="https://www.paypalobjects.com/api/checkout.js"></script>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                paypal.Button.render({
                    // Configure environment
                    env: '<?php echo $paypal->paypalEnv; ?>',
                    client: {
                        sandbox: '<?php echo $paypal->paypalClientID; ?>',
                        production: '<?php echo $paypal->paypalClientID; ?>'
                    },
                    // Customize button (optional)
                    locale: 'fr_FR',
                    style: {
                        size: 'large',
                        color: 'blue',
                        shape: 'pill',
                    },
                    // Set up a payment
                    payment: function (data, actions) {
                        return actions.payment.create({
                            transactions: [{
                                amount: {
                                    total: '<?php echo $payment->price; ?>',
                                    currency: 'EUR'
                                }
                            }]
                        });
                    },
                    // Execute the payment
                    onAuthorize: function (data, actions) {
                        return actions.payment.execute()
                            .then(function () {
                                window.location = "/billing/topup/paypal/process/"+data.paymentID+"/"+data.paymentToken+"/"+data.payerID+"/<?php echo $payment->uuid; ?>"
                            });
                    }
                }, '#paypal-button');
            </script>
        </div>
    </main>



<?php require 'templates/layouts/footer.php'; ?>