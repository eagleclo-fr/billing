<?php
use src\User\Session;
use src\User\User;
use src\Cloud\CloudBilling;
use src\Helper\FlashService;

$session = new Session();
$user = new User();
$cloudbilling = new CloudBilling();

$session->getSession();
$user->userInfo($session->userid);

if(isset($_POST['addPromo'])){
    $code = htmlspecialchars($_POST['code']);
    $cloudbilling->verifyPromo($code, $_GET['offer'], $_GET['image'], $_GET['locate']);
}

if(isset($_POST['retrycode'])){
    $cloudbilling->retryPromo($_GET['offer'], $_GET['image'], $_GET['locate']);
}

if(isset($_POST['delpromo'])){
    $cloudbilling->retryPromo($_GET['offer'], $_GET['image'], $_GET['locate']);
}

if (isset($_GET['promo'])) {
    $cloudbilling->getOffer($_GET['offer']);
    $cloudbilling->getPrice($cloudbilling->price, $_GET['promo']);
    if(isset($_POST['deploy'])){
        $cloudbilling->deployCloud($session->userid, $_GET['locate'], $_GET['image'], $_GET['offer'], $cloudbilling->TotalPrice);
    }
} else {
    if(isset($_GET['offer'])) {
        $cloudbilling->getOffer($_GET['offer']);
        if (isset($_POST['deploy'])) {
            $cloudbilling->deployCloud($session->userid, $_GET['locate'], $_GET['image'], $_GET['offer'], $cloudbilling->price);
        }
    }
}
?>
<?php require 'templates/layouts/header.php'; ?>
    <main id="main" class="main">
        <div class="row">
            <div class="col-12">
                <h5 class="card-title"><i class="bi bi-plus-circle-fill"></i> Déployer votre VPS</h5>
                <hr>
                <h5><i class="bi bi-1-square-fill"></i> SÉLECTIONNER UNE LOCALISATION</h5>
                <div class="row">
                    <?= $cloudbilling->getAllLocates(); ?>
                </div>

                <?php if(isset($_GET['locate'])){ ?>
                    <hr>
                    <h5><i class="bi bi-2-square-fill"></i> SÉLECTIONNER UN SYSTÈME</h5>
                    <div class="row">
                        <?= $cloudbilling->getImages($_GET['locate']); ?>
                    </div>

                <?php } ?>

                <?php if(isset($_GET['offer'])){ ?>
                    <?php $cloudbilling->getOffer($_GET['offer']) ?>
                    <hr>
                    <h5><i class="bi bi-3-square-fill"></i> RÉCAPITULATIF DE VOTRE OFFRE</h5>
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <br><img src="/templates/assets/img/cloud.png" width="130px" height="100px">
                                        </div>
                                        <div class="col-sm-6">
                                            <?php if (isset($_GET['promo'])) { ?>
                                                <br><h3><?= $cloudbilling->plan_name ?> (<?= number_format($cloudbilling->TotalPrice, 2, ',', ' ') ?>€/mo) <br><span class="badge bg-primary bg-pill">Réduction de <?= $cloudbilling->getValue ?>%</span></h3><small><i class="bi bi-cpu-fill"></i> VCPU : <?= $cloudbilling->cores ?> <?= $cloudbilling->cpu_model ?><br><i class="bi bi-memory"></i> RAM : <?= $cloudbilling->ram ?>Mo<br><i class="bi bi-device-hdd-fill"></i> Stockage : <?= $cloudbilling->space ?>Go <?= $cloudbilling->hdd_model ?></small>
                                            <?php } else { ?>
                                                <br><h3><?= $cloudbilling->plan_name ?> (<?= number_format($cloudbilling->price, 2, ',', ' ') ?>€/mo)</h3><small><i class="bi bi-cpu-fill"></i> VCPU : <?= $cloudbilling->cores ?> <?= $cloudbilling->cpu_model ?><br><i class="bi bi-memory"></i> RAM : <?= $cloudbilling->ram ?>Mo<br><i class="bi bi-device-hdd-fill"></i> Stockage : <?= $cloudbilling->space ?>Go <?= $cloudbilling->hdd_model ?></small>
                                            <?php } ?>
                                        </div>
                                        <div class="col-sm-3">
                                            <br><br><a href="/cloud/deploy?locate=<?= $_GET['locate'] ?>&image=<?= $_GET['image'] ?>" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Modifier</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h5><i class="bi bi-4-square-fill"></i> FINALISER ET DÉPLOYER</h5>
                    <?php FlashService::flash(); ?>
                    <div class="row">
                        <div class="col-6">
                            <?php
                            if($user->solde < $cloudbilling->price){ ?>
                                <div class="alert alert-warning" role="alert">
                                    Attention, vous n'avez pas assez de solde ! <a href="/billing/topup" class="btn btn-primary btn-sm">Créditer</a>
                                </div>
                            <?php } ?>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <br><h4>Vous avez <?= number_format($user->solde, 2, '.', ' ') ?>€ crédits</h4>
                                        </div>
                                        <form method="post">
                                            <div class="col-sm-6">
                                                <br><button type="submit" name="deploy" class="btn btn-primary"><i class="bi bi-lightning-charge-fill"></i> Déployer</button>
                                                <a href="/manager" class="btn btn-danger"><i class="bi bi-trash3"></i> Annuler</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-body">
                                    <form method="post">
                                        <div class="row">
                                            <?php if(isset($_GET['promo'])){ ?>
                                                <div class="col-sm-6">
                                                    <br>
                                                    <span class="badge bg-primary bg-pill">Réduction de <?= $cloudbilling->getValue ?>%</span>
                                                    <h5>Price avec réduction : <?= $cloudbilling->TotalPrice ?>€/mo</h5>
                                                </div>
                                                <div class="col-sm-6">
                                                    <br>
                                                    <button type="submit" name="delpromo" class="btn btn-danger">Supprimer</button>
                                                    <br><br>
                                                    <button type="submit" name="retrycode" class="btn btn-primary">Entrer un autre code promo</button>
                                                </div>
                                            <?php } else { ?>
                                                <div class="col-sm-6">
                                                    <br>
                                                    <input type="text" class="form-control" name="code" id="code" placeholder="Entrer un code promo">
                                                </div>
                                                <div class="col-sm-6">
                                                    <br>
                                                    <button type="submit" name="addPromo" class="btn btn-primary">Appliquer la réduction</button>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } else if(isset($_GET['image'])){ ?>
                    <hr>
                    <h5><i class="bi bi-3-square-fill"></i> SÉLECTIONNER UNE OFFRE</h5>
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">NOM</th>
                                        <th scope="col">VCPUs</th>
                                        <th scope="col">CPU MODEL</th>
                                        <th scope="col">RAM</th>
                                        <th scope="col">SSD</th>
                                        <th scope="col">TRAFIC</th>
                                        <th scope="col">PRIX</th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?= $cloudbilling->getOffers($_GET['locate'], $_GET['image']); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>

<?php require 'templates/layouts/footer.php'; ?>