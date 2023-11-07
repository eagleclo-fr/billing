<?php
use src\User\Session;
use src\User\User;
use src\Billing\Invoices;
use src\Billing\Payment;
use src\Helper\CSRF;
use src\Helper\FlashService;
use src\Billing\Card;

$session = new Session();
$user = new User();
$card = new Card();

$session->getSession();
$user->userInfo($session->userid);
$invoice = new Invoices();
$payment = new Payment();

$id = $params['id'];
if(isset($id)) {
    $payment->getPayment($session->userid, 'stripe', $id);
}
if(isset($_POST['stripeToken'])){
    $card->Payment($session->userid, $user->mail, $payment->price, $id);
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
                            <link rel="stylesheet" href="/components/stripe/card.js" />
                            <script src="https://js.stripe.com/v3/"></script>
                            <form action="/billing/topup/stripe/<?= $id ?>" method="post" id="payment-form">
                                <div class="form-row">
                                    <label for="card-element">
                                        <br>
                                        <center><h4>Veuillez remplir vos coordonnées bancaire.</h4></center>
                                    </label>
                                    <div id="card-element">
                                    </div>
                                    <div id="card-errors" role="alert"></div>
                                </div>
                                <br>
                                <button class="btn btn-primary btn-lg" name="linkcc"><i class="fa fa-credit-card" aria-hidden="true"></i> Payer maintenant <?= number_format($payment->price, 2, ',', ' ');?>€</button>
                                <br>
                            </form>
                            <script src="/components/stripe/card.js"></script>
                        </div>
                    </div>
                </div>
            </div>


    </main>



<?php require 'templates/layouts/footer.php'; ?>