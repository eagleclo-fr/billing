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

if (isset($_POST['create'])) {
    $department = htmlspecialchars(strip_tags($_POST['department']));
    $title = htmlspecialchars(strip_tags($_POST['title']));
    $message = htmlspecialchars($_POST['message']);
    $support->addTicket($department, $title, $message, $session->userid, $user->mail);
}

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

        <div class="col-md-12">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <form method="post">
                    <div class="row">
                        <div class="card-body">
                            <div class="row" style="margin-bottom: 15px;">
                                <section class="col-lg-4" id="form-vertical">
                                    <div class="form-group">
                                        <br>
                                        <label for="sujet">Sujet</label>
                                        <input type="text" name="title" class="form-control" id="title" placeholder="Sujet">
                                    </div>

                                    <div class="form-group">
                                        <br>
                                        <label for="exampleInputPassword1">Département</label>
                                        <select class="form-control" name="department" id="department">
                                            <option value="Commercial">Commercial</option>
                                            <option value="Facturation">Facturation</option>
                                            <option value="Technique">Technique</option>
                                        </select>
                                    </div>
                                </section>
                                <section class="col-lg-8" id="form-vertical">
                                    <div class="form-group">
                                        <br>
                                        <label class="control-label" for="message">Informations complémentaires</label>
                                        <textarea type="text" class="form-control" name="message" id="message" rows="5" placeholder="Merci de détailler au maximum votre problème.
Ne donnez aucune information confidentielle (mots de passe, ...), nos techniciens ont toutes les informations nécessaires pour régler votre problème."></textarea>
                                    </div>
                                </section>
                            </div>
                            <br>
                            <?php FlashService::flash(); ?>
                            <input type="hidden" name="_token" value="<?php echo CSRF::token()?>">
                            <button type="submit" name="create" class="btn btn-primary btn-lg">Créer une demande d'assistance</button>
                            </form>
                            </div>
                        </div>
                    </div>
                </main>



<?php require 'templates/layouts/footer.php'; ?>