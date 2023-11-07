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
$games->getAccountPterodactyl($session->userid);
?>
<?php require 'templates/layouts/header.php'; ?>

    <main id="main" class="main">

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
                            <a href="/games/servers/<?= $games->id_service; ?>/overview" class="btn btn-primary"><i class="bi bi-gear"></i> Tableau de bord</a>
                            <hr>
                            <a href="/billing/renew/<?= $games->id_service; ?>" class="btn btn-warning"><i class="bi bi-arrow-repeat"></i> Renouvellement</a>
                            <hr>
                            <a href="/games/servers/<?= $games->id_service; ?>/identifiers" class="btn btn-dark"><i class="bi bi-key"></i> Panel</a>
                            <hr>
                            <a href="/games/servers/<?= $games->id_service; ?>/rebuild" class="btn btn-primary"><i class="bi bi-download"></i> Réinstallation</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-key"></i> Identifiants</h5>
                        <div class="row">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Identifiant pterodactyl</th>
                                    <th scope="col">Mot de passe pterodactyl</th>
                                    <th scope="col">Panel</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?= $games->emailPterodactyl ?></td>
                                    <td><?= $games->passwordPterodactyl ?></td>
                                    <td><a href="https://game.powerful-hosting.fr">https://game.powerful-hosting.fr</a></td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require 'templates/layouts/footer.php'; ?>