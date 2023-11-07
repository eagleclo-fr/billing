<?php
use src\User\addons\PaginatedQuery;
use src\User\Session;
use src\User\User;

$session = new Session();
$user = new User();

$session->getSession();
$user->userInfo($session->userid);

$paginatedQuery = new PaginatedQuery("SELECT id as postId, firstname, lastname, mail, country, region, city, address, confirm, banned FROM `users` ORDER BY id DESC ", "SELECT COUNT(id) FROM users", $user->pdo);
$posts = $paginatedQuery->getItems();
$link = "/admin/users";

?>
<?php require 'templates/layouts/header-admin.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Utilisateurs</h1>
            <h5>Administrez les différents utilisateurs du système.</h5>
            <br>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <?php if(isset($_GET['state']) == "success"){ ?>
                    <div class="alert alert-success" role="alert">
                        L'enregistrement a été modifié.
                    </div>
                <?php } ?>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr class="headings">
                                    <th class="column-title">ID </th>
                                    <th class="column-title">Prénom </th>
                                    <th class="column-title">Nom </th>
                                    <th class="column-title">Email </th>
                                    <th class="column-title">État </th>
                                    <th class="column-title no-link last"><span class="nobr">Action</span></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($posts as $post) : ?>
                                <tr class="even pointer">
                                    <td class=" "><?= $post->postId ?></td>
                                    <td class=" "><?= $post->firstname ?></td>
                                    <td class=" "><?= $post->lastname ?></td>
                                    <td class=" "><?= $post->mail ?></td>
                                    <td class=""><?= $post->confirm ? '<span class="btn btn-success btn-sm">CONFIRME</span>' : '<span class="btn btn-warning btn-sm">ATTENTE</span>' ?></td>
                                    <td class="">
                                        <a href="/admin/users/<?= $post->postId ?>" class="btn btn-primary btn-sm"><i class="bi bi-pencil-square"></i></a>
                                        <?php if($post->banned == 0){ ?>
                                        <a href="/admin/users/<?= $post->postId ?>/banned" class="btn btn-danger btn-sm"><i class="bi bi-shield-fill-exclamation"></i></a>
                                        <?php } else { ?>
                                        <a href="/admin/users/<?= $post->postId ?>/unbanned" class="btn btn-success btn-sm"><i class="bi bi-play-circle-fill"></i></a>
                                        <?php } ?>
                                    </td>
                                    <?php endforeach ?>
                                </tr>
                                </tbody>
                            </table>
                            <?= $paginatedQuery->previousLink($link); ?>
                            <?= $paginatedQuery->nextLink($link); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->

<?php require 'templates/layouts/footer.php'; ?>