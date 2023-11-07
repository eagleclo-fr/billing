<?php
use src\User\Session;
use src\User\User;
use src\Cloud\Cloud;

$session = new Session();
$user = new User();
$cloud = new Cloud();

$session->getSession();
$user->userInfo($session->userid);

?>
<?php require 'templates/layouts/header.php'; ?>

    <main id="main" class="main">
        <div class="row">

            <div class="col-12">
                <div class="card">
                    <div class="card-body"><center>
                        <h5 class="card-title">Acheter un produit ?</h5>
                        <p class="card-text"><a href="/cloud/deploy" class="btn btn-primary">Acheter un produit</a></p>
                    </div></center>
                </div><!-- End Card with titles, buttons, and links -->
            </div>

            <hr>

            <?= $cloud->getAllCloud($session->userid) ?>
        </div>
    </main>

<?php require 'templates/layouts/footer.php'; ?>