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
                        <a href="/billing" class="nav-link" aria-controls="balance" aria-selected="true"><i class="bi bi-currency-euro"></i> Balance</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="/billing/topup" class="nav-link" aria-controls="topup" aria-selected="false"><i class="bi bi-card-heading"></i> Créditer</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a href="/billing/invoices" class="nav-link active" aria-controls="invoices" aria-selected="false"><i class="bi bi-files"></i> Factures</a>
                    </li>
                </ul>
                </div>
            </div>

        <hr>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Produit</th>
                            <th scope="col">Prix</th>
                            <th scope="col">Date de création</th>
                            <th scope="col">Date de création</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?= $invoice->getAllInvoices($session->userid); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>



<?php require 'templates/layouts/footer.php'; ?>