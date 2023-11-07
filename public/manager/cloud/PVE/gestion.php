<?php
use src\User\Session;
use src\User\User;
use src\Cloud\Cloud;
use src\Helper\FlashService;

$session = new Session();
$user = new User();
$cloud = new Cloud();

$session->getSession();
$user->userInfo($session->userid);

$cloud->getCloud($session->userid, $params['idservice']);

if(isset($_POST['reboot'])){
    $cloud->action('reboot', $cloud->vm_id, $cloud->id_service, $session->userid, $cloud->serverVM);
}

if(isset($_POST['stop'])){
    $cloud->action('stop', $cloud->vm_id, $cloud->id_service, $session->userid, $cloud->serverVM);
}

if(isset($_POST['start'])){
    $cloud->action('start', $cloud->vm_id, $cloud->id_service, $session->userid, $cloud->serverVM);
}

?>
<?php require 'templates/layouts/header.php'; ?>

    <main id="main" class="main">

        <?php if($cloud->getCurrentState == "created"){ ?>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?= $cloud->getStatusVM ?> <?= $cloud->id_service ?> <strong><?php if($cloud->statusVM == "running"){ ?><small>(Uptime: <?= $cloud->uptimeVM ?>)</small><?php } ?></strong></h4>
                    </div>
                </div>
                <?php FlashService::flash(); ?>
            </div>

            <div class="col-sm-2">
                <div class="card">
                    <div class="card-body">
                        <br>
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/overview" class="btn btn-dark"><i class="bi bi-gear"></i> Tableau de bord</a>
                            <hr>
                            <a href="/billing/renew/<?= $cloud->id_service; ?>" class="btn btn-warning"><i class="bi bi-arrow-repeat"></i> Renouvellement</a>
                            <hr>
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/identifiers" class="btn btn-primary"><i class="bi bi-key"></i> Identifiants</a>
                            <hr>
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/snapshots" class="btn btn-primary"><i class="bi bi-cloudy-fill"></i> Snapshots</a>
                            <hr>
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/network" class="btn btn-primary"><i class="bi bi-diagram-2-fill"></i> Réseaux</a>
                            <hr>
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/power" class="btn btn-primary"><i class="bi bi-lightning-charge-fill"></i> Actions</a>
                            <hr>
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/rebuild" class="btn btn-primary"><i class="bi bi-download"></i> Réinstallation</a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-gear"></i> Tableau de bord</h5>
                        <div class="row">
                                <div class="col-sm-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                            <center><strong><h4><?= $cloud->plan_nameVM ?></h4><small>#<?= $cloud->server ?></small></strong></center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                            <center><strong><h4><i class="bi bi-cpu-fill"></i> <?= $cloud->coresVM ?></h4><small>VCPU</small></strong></center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                            <center><strong><h4><i class="bi bi-memory"></i> <?= $cloud->ramVM ?>Mo</h4><small>RAM</small></strong></center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                            <center><strong><h4><i class="bi bi-device-hdd-fill"></i> <?= $cloud->spaceVM ?>GB</h4><small><?= $cloud->hdd_modelVM ?></small></strong></center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                            <center><strong><h4><i class="bi bi-currency-euro"></i> <?= $cloud->price ?>/mo</h4><small>Prix</small></strong></center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                            <form method="post">
                                            <h5><?= $cloud->getStatusVM ?> <?= $cloud->getStatusVMWriter ?></h5><hr>
                                            <?php if($cloud->statusVM == "running"){ ?>
                                            <button type="submit" class="btn btn-danger" name="stop"><i class="bi bi-toggle2-off"></i> Eteindre</button>
                                            <?php } else { ?>
                                            <button type="submit" class="btn btn-success" name="start"><i class="bi bi-play"></i> Démarrer</button>
                                            <?php } ?>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="bi bi-activity"></i> Activités du serveur</h5>
                                        <?= $cloud->getTasks($cloud->idservice); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="bi bi-map"></i> Localisation du datacentre</h5>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <small><strong><i class="bi bi-building"></i> Datacentre</strong><br><?= $cloud->datacenterPVE ?></small>
                                                <br><br>
                                                <small><strong><i class="bi bi-pin-map-fill"></i> Région</strong><br><?= $cloud->cityPVE ?></small>
                                            </div>
                                            <div class="col-sm-6">
                                                <small><strong><i class="bi bi-flag-fill"></i> Pays</strong><br><?= $cloud->countryPVE ?></small>
                                                <br><br>
                                                <small><strong><i class="bi bi-hdd-network-fill"></i> Réseaux</strong><br><?= $cloud->networkPVE ?></small>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <?php } else if($cloud->getCurrentState == "pending"){ ?>

        <div class="row">
            <div class="col-sm-12">
                <br><br><br>
                <center>
                    <div class="spinner-border" style="width: 50px; height: 50px;" role="status">
                    <span class="visually-hidden"></span></div>
                    <h2><br>Installation en cours de votre VPS</h2>
                    <br><br>
                    <p class="uppercase">Votre serveur est actuellement en cours d'installation, veuillez patienter.<br>
                        Vous serez redirigé dès la fin de l'installation.
                    </p>
                    <meta http-equiv="refresh" content="10"; URL="">
                </center>
            </div>
        </div>

    <?php } ?>

    </main>

<?php require 'templates/layouts/footer.php'; ?>