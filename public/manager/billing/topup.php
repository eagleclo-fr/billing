<?php
use src\User\Session;
use src\User\User;
use src\Billing\Invoices;
use src\Billing\Payment;
use src\Helper\CSRF;
use src\Helper\FlashService;

$session = new Session();
$user = new User();

$session->getSession();
$user->userInfo($session->userid);
$invoice = new Invoices();
$payment = new Payment();

if(isset($_POST['addCredit'])){
    $credit = htmlspecialchars($_POST['credit']);
    $gateway = htmlspecialchars($_POST['gateway']);
    $payment->createPayment($session->userid, $credit, $gateway);
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

        <div class="card">
            <div class="card-body">
                <br>
                <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a href="/billing" class="nav-link" aria-controls="balance" aria-selected="true"><i class="bi bi-currency-euro"></i> Balance</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="/billing/topup" class="nav-link active" aria-controls="topup" aria-selected="false"><i class="bi bi-card-heading"></i> Créditer</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="/billing/invoices" class="nav-link" aria-controls="invoices" aria-selected="false"><i class="bi bi-files"></i> Factures</a>
                    </li>
                </ul>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-6">

                <div class="card">
                    <div class="card-header">
                        CRÉDITER VOTRE BALANCE
                    </div>
                    <div class="card-body">
                    <form method="post">
                        <div class="row">
                            <div class="col-sm-6">
                                <br>
                                <p>Choisir un montant:</p>
                                <input type="text" name="credit" class="form-control" placeholder="0" value="0.00">
                            </div>
                            <div class="col-sm-6">
                                <br>
                                <p>Votre solde actuel:</p>
                                <input type="text" class="form-control" placeholder="<?= number_format($user->solde, 2, '.', ' ') ?>€" disabled>
                                <br>
                            </div>
                            <div class="form-group">
                                <label for="gateway">Choisissez une méthode de paiement</label>
                                <select class="form-control" id="gateway" name="gateway">
                                    <option value="stripe">Carte bancaire (VISA/MasterCard/Maestro/AMEX)</option>
                                    <option value="paypal">PayPal</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <?php FlashService::flash(); ?>
                        <button type="submit" name="addCredit" class="btn btn-primary"><i class="bi bi-credit-card-fill"></i> Créditer mon compte</button>
                    </form>
                    </div>
                </div>
            </div>
        </div>


    </main>



<?php require 'templates/layouts/footer.php'; ?>