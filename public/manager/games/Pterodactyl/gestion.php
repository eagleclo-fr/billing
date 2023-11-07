<?php
use src\User\Session;
use src\User\User;
use src\Games\Games;
use src\Helper\FlashService;

$session = new Session();
$user = new User();
$games = new Games();

$session->getSession();
$user->userInfo($session->userid);

$games->getGames($session->userid, $params['idservice']);

if(isset($_POST['stop'])){
    $games->getActions($games->id_service, $session->userid, $games->external_id, 'stop');
}

if(isset($_POST['start'])){
    $games->getActions($games->id_service, $session->userid, $games->external_id, 'start');
}

if(isset($_POST['restart'])){
    $games->getActions($games->id_service, $session->userid, $games->external_id, 'restart');
}

?>
<?php require 'templates/layouts/header.php'; ?>

    <main id="main" class="main">

    <?php if($games->statusGames != null){ ?>

            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><?= $games->getstatusGames ?> (<?= $games->getstatusGamesWrite ?>) <?= $games->id_service ?></h4>
                        </div>
                    </div>
                    <?php FlashService::flash(); ?>
                </div>

                <div class="col-sm-2">
                    <div class="card">
                        <div class="card-body">
                            <br>
                            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                <a href="/games/servers/<?= $games->id_service; ?>/overview" class="btn btn-dark"><i class="bi bi-gear"></i> Tableau de bord</a>
                                <hr>
                                <a href="/billing/renew/<?= $games->id_service; ?>" class="btn btn-warning"><i class="bi bi-arrow-repeat"></i> Renouvellement</a>
                                <hr>
                                <a href="/games/servers/<?= $games->id_service; ?>/identifiers" class="btn btn-primary"><i class="bi bi-key"></i> Panel</a>
                                <hr>
                                <a href="/games/servers/<?= $games->id_service; ?>/rebuild" class="btn btn-primary"><i class="bi bi-download"></i> Réinstallation</a>
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
                                            <center><strong><h4><?= $games->plan_nameGames ?></h4><small>#<?= $games->serverGames ?></small></strong></center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                            <center><strong><h4><i class="bi bi-memory"></i> <?= $games->ramGames ?>Mo</h4><small>RAM</small></strong></center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                            <center><strong><h4><i class="bi bi-device-hdd-fill"></i> <?= $games->spaceGames ?>GB</h4><small><?= $games->hdd_modelGames ?></small></strong></center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                            <center><strong><h4><i class="bi bi-currency-euro"></i> <?= $games->price ?>/mo</h4><small>Prix</small></strong></center>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                            <form method="post">
                                                <h5><?= $games->getstatusGames ?> <?= $games->getstatusGamesWrite ?></h5><hr>
                                                <?php if($games->statusGames == "running"){ ?>
                                                    <button type="submit" class="btn btn-danger" name="stop"><i class="bi bi-toggle2-off"></i> Eteindre</button>
                                                    <button type="submit" class="btn btn-warning" name="restart"><i class="bi bi-toggle2-off"></i> Redémarrer</button>
                                                <?php } else if($games->statusGames == "starting"){ ?>
                                                    <button class="btn btn-danger" disabled><i class="bi bi-toggle2-off"></i> Eteindre</button>
                                                    <button class="btn btn-warning" disabled><i class="bi bi-toggle2-off"></i> Redémarrer</button>
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
                                        <?= $games->getTasks($games->idservice); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <?php } else { ?>

        <div class="row">
            <div class="col-sm-12">
                <br><br><br>
                <center>
                    <div class="spinner-border" style="width: 50px; height: 50px;" role="status">
                        <span class="visually-hidden"></span></div>
                    <h2><br>Installation en cours de votre serveur</h2>
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