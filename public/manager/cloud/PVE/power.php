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

if(isset($_POST['Shutdown'])){
    $cloud->action('shutdown', $cloud->vm_id, $cloud->id_service, $session->userid, $cloud->serverVM);
}

if(isset($_POST['start'])){
    $cloud->action('start', $cloud->vm_id, $cloud->id_service, $session->userid, $cloud->serverVM);
}

?>
<?php require 'templates/layouts/header.php'; ?>

    <main id="main" class="main">

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
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/overview" class="btn btn-primary"><i class="bi bi-gear"></i> Tableau de bord</a>
                            <hr>
                            <a href="/billing/renew/<?= $cloud->id_service; ?>" class="btn btn-warning"><i class="bi bi-arrow-repeat"></i> Renouvellement</a>
                            <hr>
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/identifiers" class="btn btn-primary"><i class="bi bi-key"></i> Identifiants</a>
                            <hr>
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/snapshots" class="btn btn-primary"><i class="bi bi-cloudy-fill"></i> Snapshots</a>
                            <hr>
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/network" class="btn btn-primary"><i class="bi bi-diagram-2-fill"></i> Réseaux</a>
                            <hr>
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/power" class="btn btn-dark"><i class="bi bi-lightning-charge-fill"></i> Actions</a>
                            <hr>
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/rebuild" class="btn btn-primary"><i class="bi bi-download"></i> Réinstallation</a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-lightning-charge-fill"></i> Actions</h5>
                        <?php if ($cloud->statusVM == "running") { ?>
                            <div class="row">
                                <p>Le bouton "Shutdown" enverra un signal ACPI à votre serveur. Si votre serveur utilise une configuration standard, il effectuera un arrêt progressif.</p>
                                <form method="post">
                                    <button type="submit" name="Shutdown" class="btn btn-danger"><i class="bi bi-x-octagon-fill"></i> Shutdown</button>
                                </form>
                            </div>
                        <?php } else { ?>
                            <div class="row">
                                <p>Votre serveur est éteint. Veuillez noter que nous devons toujours facturer les serveurs éteints.</p>
                                <form method="post">
                                    <button type="submit" name="start" class="btn btn-success"><i class="bi bi-play-fill"></i> Démarrer</button>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require 'templates/layouts/footer.php'; ?>