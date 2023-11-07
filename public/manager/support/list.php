<?php
use src\User\Session;
use src\User\User;
use src\Helper\CSRF;
use src\Helper\FlashService;
use src\Support\Support;

$session = new Session();
$user = new User();
$support = new Support();

$session->getSession();
$user->userInfo($session->userid);


?>
<?php require 'templates/layouts/header.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Mes demandes d'assistance</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/manager">Dashboard</a></li>
                    <li class="breadcrumb-item active">Mes demandes d'assistance</li>
                </ol>
            </nav>
        </div>

        <hr>
        <a href="/support/create" class="btn btn-primary"><i class="bi bi-envelope-plus-fill"></i> Créer une demande d'assistance</a>
        <br><br>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Numéro de ticket</th>
                            <th scope="col">Sujet</th>
                            <th scope="col">Département</th>
                            <th scope="col">Date de création</th>
                            <th scope="col">Date de dernière mise à jour</th>
                            <th scope="col">Etat</th>
                            <th scope="col"></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?= $support->getAllTickets($session->userid) ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>



<?php require 'templates/layouts/footer.php'; ?>