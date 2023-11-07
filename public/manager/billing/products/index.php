<?php
use src\User\Session;
use src\User\User;

$session = new Session();
$user = new User();

$session->getSession();
$user->userInfo($session->userid);

?>
<?php require 'templates/layouts/header.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Bouti</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">

                <!-- Left side columns -->
                <div class="col-lg-8">
                    <div class="row">

                        <h2>Bienvenue <?= $user->firstname ?>, <?= $user->lastname ?></h2>

                        <h4>Espace Client en version BETA 0.1</h4>

                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->

<?php require 'templates/layouts/footer.php'; ?>