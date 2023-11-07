<?php
use src\User\Session;
use src\User\User;
use src\Billing\Payment;
use src\Helper\CSRF;
use src\Helper\FlashService;

$session = new Session();
$user = new User();

$session->getSession();
$user->userInfo($session->userid);
$payment = new Payment();

$id = $params['id'];
if(isset($id)) {
    $payment->getPayment($session->userid, 'check', $id);
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

        <?php if($payment->status == "success"){ ?>
            <div class="col-md-12">
                <br>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <br>
                        <center><h2><i class="fa fa-check-circle" aria-hidden="true"></i> Votre transaction (#<?= $id ?>) a été acceptée !</h2></center>
                        <center><h3><strong>Merci d'avoir choisi CENTERCLOUD !</strong></h3></center>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
            <br>
            <div class="card border-0 shadow">
                <div class="card-body">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row">
                        <li class="nav-item me-sm-2">
                            <a class="nav-link mb-3 mb-md-0 d-flex align-items-center justify-content-center" href="/billing/invoices">
                                <img src="/templates/assets/img/parchment.png" style="height: 35px; width: auto;"> HISTORIQUE
                            </a>
                        </li>
                        <li class="nav-item me-sm-2">
                            <a class="nav-link mb-3 mb-md-0 d-flex align-items-center justify-content-center" href="/account">
                                <img src="/templates/assets/img/user.png" style="height: 35px; width: auto;"> MON COMPTE
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        <?php } else if($payment->status == "pending"){ ?>

            <div class="col-md-12">
                <br>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <center><img src="/templates/assets/img/close.png" height="200px" width="200px">
                            <h2><i class="fa fa-close" aria-hidden="true"></i> Oups, une erreur est survenue.</h2>
                            <h3><strong>[404]</strong><br>ERROR</h3></center>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <br>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row">
                            <li class="nav-item me-sm-2">
                                <a class="nav-link mb-3 mb-md-0 d-flex align-items-center justify-content-center" href="/billing/invoices">
                                    <img src="/templates/assets/img/parchment.png" style="height: 35px; width: auto;"> HISTORIQUE
                                </a>
                            </li>
                            <li class="nav-item me-sm-2">
                                <a class="nav-link mb-3 mb-md-0 d-flex align-items-center justify-content-center" href="/account">
                                    <img src="/templates/assets/img/user.png" style="height: 35px; width: auto;"> MON COMPTE
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            </div>

        <?php } else { ?>

            <div class="col-md-12">
                <br>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <center><img src="/templates/assets/images/close.png" height="200px" width="200px">
                            <h2><i class="fa fa-close" aria-hidden="true"></i> Internal server error.</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <br>
                <div class="card border-0 shadow">
                    <div class="card-body">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row">
                            <li class="nav-item me-sm-2">
                                <a class="nav-link mb-3 mb-md-0 d-flex align-items-center justify-content-center" href="/manager/billing/history/">
                                    <img src="/templates/assets/images/parchment.png" style="height: 35px; width: auto;"> HISTORIQUE
                                </a>
                            </li>
                            <li class="nav-item me-sm-2">
                                <a class="nav-link mb-3 mb-md-0 d-flex align-items-center justify-content-center" href="/manager/account/user/profile/">
                                    <img src="/templates/assets/images/user.png" style="height: 35px; width: auto;"> MON COMPTE
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        <?php } ?>

    </main>



<?php require 'templates/layouts/footer.php'; ?>