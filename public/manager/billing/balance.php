<?php
use src\User\Session;
use src\User\User;
use src\Billing\Invoices;
use src\Helper\CSRF;
use src\Helper\FlashService;

$session = new Session();
$user = new User();

$session->getSession();
$user->userInfo($session->userid);
$invoice = new Invoices();


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
                        <a href="/billing" class="nav-link active" aria-controls="balance" aria-selected="true"><i class="bi bi-currency-euro"></i> Balance</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="billing/topup" class="nav-link" aria-controls="topup" aria-selected="false"><i class="bi bi-card-heading"></i> Créditer</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="/billing/invoices" class="nav-link" aria-controls="invoices" aria-selected="false"><i class="bi bi-files"></i> Factures</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                VOS CRÉDITS
            </div>
            <div class="card-body">
                <h5 class="card-title">Vos crédits sont utilisé pour payer vos factures / services</h5>
                <h4>Vous avez <?= number_format($user->solde, 2, '.', ' ') ?>€ sur votre compte</h4>
                <hr>
                <a href="/billing/topup" class="btn btn-primary">Créditer mon compte</a>
            </div>
        </div>
        

    </main>



<?php require 'templates/layouts/footer.php'; ?>