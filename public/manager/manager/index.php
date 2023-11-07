<?php
use src\User\Session;
use src\User\User;
use src\Billing\Invoices;

$session = new Session();
$user = new User();
$invoice = new Invoices();

$session->getSession();
$user->userInfo($session->userid);

?>
<?php require 'templates/layouts/header.php'; ?>

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <h2><?php if($user->firstname == null){ echo 'Bienvenue !'; } else { echo 'Bienvenue '.$user->firstname.', '.$user->lastname.''; } ?></h2>

                    <h4>Espace Client en version BETA 0.2 (Release: 16/08/2022)</h4>

                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="row">
                <div class="col-sm-8">
                    <br>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><strong>Mes dernières factures</strong></h5>
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
                                    <?= $invoice->getAllInvoicesLimit($session->userid, '5'); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <br>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><strong>Mon profil</strong></h5>
                            <div class="row">
                                <h4><strong><?php if($user->firstname == null){ echo 'Jean, Dupont'; } else { echo ''.$user->firstname.', '.$user->lastname.''; } ?></strong></h4>
                                <h4>ID Client : <?= $user->customerid ?></h4>
                                <hr>
                                <h4><strong>Mes crédits :</strong> <?= number_format($user->solde, 2, '.', ' ') ?>€</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End #main -->

<?php require 'templates/layouts/footer.php'; ?>