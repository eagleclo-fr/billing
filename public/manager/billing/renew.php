<?php
use src\User\Session;
use src\User\User;
use src\Helper\FlashService;
use src\Billing\Renew;

$session = new Session();
$user = new User();
$renew = new Renew();

$session->getSession();
$user->userInfo($session->userid);

$renew->getService($session->userid, $params['idservice']);

if(isset($_POST['renew'])){
    $renew->renewService($session->userid, $renew->id_service, '1', $renew->expiry, $renew->price);
}

?>
<?php require 'templates/layouts/header.php'; ?>

<main id="main" class="main">

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Renouvellement de votre service</h4>
                    <h5><strong>Produit :</strong> <?= $renew->id_service ?></h5>
                    <h5><strong>Expiration le :</strong> <?= date('d/m/Y', strtotime($renew->expiry)); ?></h5>
                    <h5><strong>Renouvellement pour :</strong> 1 mois</h5>
                    <h5><strong>Prix pour 1 mois : </strong> <?= $renew->price ?></h5>
                    <hr>
                    <h5><strong>Date d'expiration après renouvellement :</strong> <?php $time = strtotime($renew->expiry); $afterDate = date("Y-m-d", strtotime("+1 month", $time)); echo date('d/m/Y', strtotime($afterDate)); ?></h5>
                </div>
            </div>
            <?php FlashService::flash(); ?>
        </div>

        <div class="col-md-12">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="panel-heading">
                        <br>
                        <h3><strong>Procéder au paiement</strong></h3>
                    </div>
                    <hr>
                    <form method="post">
                    <a href="/" class="btn btn-danger"><i class="bi bi-x-octagon-fill"></i> Annuler</a> <button type="submit" name="renew" class="btn btn-primary"><i class="bi bi-arrow-repeat"></i> Renouveler mon service</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>
    <p>Tout incident de paiement peut entraîner une suspension de services et est passible de pénalités de retard calculées sur la base de trois fois le taux d’intérêt légal en vigueur en France, avec un montant d’indemnité forfaitaire minimum de 40 euros.</p>
    <br>
    <p><center><strong>GENIUSWEER - 78 Avenue des Champs-Élysées, Bureau 562, 75008 Paris - France</strong></center></p>
    <p><center>ASSOCIATION DECLARE - 88260405100012</center></p>
    <p><center>N°TVA FR3688260405</center></p>
</main>

<?php require 'templates/layouts/footer.php'; ?>
