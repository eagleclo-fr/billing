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

$invoice->getInvoice($session->userid, $params['id'])


?>
<?php require 'templates/layouts/header.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Factures</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/manager">Dashboard</a></li>
                    <li class="breadcrumb-item active">Factures</li>
                </ol>
            </nav>
        </div>

        <div class="card">
            <div class="card-body mx-4">
                <div class="container">
                    <p class="my-5 mx-5" style="font-size: 30px;">Merci de votre achat</p>
                    <div class="row">
                        <ul class="list-unstyled">
                            <li class="text-black"><?= $user->firstname ?>, <?= $user->lastname ?></li>
                            <li class="text-muted mt-1"><span class="text-black">Facture</span> #<?= $invoice->id ?></li>
                            <li class="text-black mt-1"><?= date('d/m/Y à H:i', strtotime($invoice->date_created)); ?></li>
                        </ul>
                        <hr>
                        <div class="col-xl-10">
                            <p><?= $invoice->product ?></p>
                        </div>
                        <div class="col-xl-2">
                            <p class="float-end"><?= number_format($invoice->price, 2, ',', ' ') ?>€
                            </p>
                        </div>
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-xl-2">
                            <p class="float-end">
                            </p>
                        </div>
                        <hr style="border: 2px solid black;">
                    </div>
                    <div class="row text-black">

                        <div class="col-xl-12">
                            <p class="float-end fw-bold">Total TTC: <?= number_format($invoice->price, 2, ',', ' ') ?>€
                            </p>
                        </div>
                        <hr style="border: 2px solid black;">
                    </div>
                    <br><p>Tout incident de paiement peut entraîner une suspension de services et est passible de pénalités de retard calculées sur la base de trois fois le taux d’intérêt légal en vigueur en France, avec un montant d’indemnité forfaitaire minimum de 40 euros.</p>
                    <br>
                    <p><center><strong>GENIUSWEER - 78 Avenue des Champs-Élysées, Bureau 562, 75008 Paris - France</strong></center></p>
                    <p><center>ASSOCIATION DECLARE - 88260405100012</center></p>
                    <p><center>N°TVA FR3688260405</center></p>

                </div>
            </div>
        </div>

    </main>



<?php require 'templates/layouts/footer.php'; ?>