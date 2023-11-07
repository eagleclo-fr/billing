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

if(isset($_POST['rebuild'])){
    $image = htmlspecialchars($_POST['image']);
    $cloud->reinstallVM($cloud->id_service, $cloud->vm_id, $session->userid, $image);
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
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/power" class="btn btn-primary"><i class="bi bi-lightning-charge-fill"></i> Actions</a>
                            <hr>
                            <a href="/cloud/servers/<?= $cloud->id_service; ?>/rebuild" class="btn btn-dark"><i class="bi bi-download"></i> Réinstallation</a>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-download"></i> Réinstallation</h5>
                        <p>La reconstruction de votre serveur l'éteindra et écrasera son disque avec l'image que vous sélectionnez.</p>
                        <form method="post">
                        <div class="row">
                            <div class="col-sm-10">
                                <select class="form-select form-select-lg mb-3" aria-label=".form-select-lg example" name="image">
                                    <option disabled>Choisissez votre image</option>
                                    <option disabled>--------------------</option>
                                    <option value="100" selected>Debian 11 (Release: 14 aout 2021)</option>
                                    <option disabled>--------------------</option>
                                    <option value="2000">Ubuntu 20.04 (Release: 23 avril 2020)</option>
                                    <option value="2100">Ubuntu 22.04 (Release: 10 aout 2022)</option>
                                    <option disabled>--------------------</option>
                                    <option value="1000">Centos 7 (Release: 24 septembre 2019)</option>
                                    <option disabled>--------------------</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button type="submit" name="rebuild" class="btn btn-danger btn-lg btn-block">Réinstaller</button>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require 'templates/layouts/footer.php'; ?>