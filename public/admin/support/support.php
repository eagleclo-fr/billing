<?php
use src\User\Session;
use src\User\User;
use src\Support\Support;
use src\Helper\CSRF;
use src\Helper\FlashService;

$session = new Session();
$user = new User();
$support = new Support();

$session->getSession();
$user->userInfo($session->userid);

$support->getTicket($session->userid, $params['id']);

if(isset($_POST['formReply'])){
    $message = htmlspecialchars($_POST['message']);
    $support->replyTicket($params['id'], $session->userid, $message);
}

if(isset($_POST['formClose'])){
    $support->closeTicket($params['id']);
}

?>
<?php require 'templates/layouts/header-admin.php'; ?>

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
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-4">
                        <br>
                        <div class="card border-0 shadow">
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <br>
                                    <li class="list-group-item">[CT-B-<?= $support->id ?>] - <?php if($support->status == "OPEN"){ echo 'Ouvert'; } else if($support->status == "CLOSE"){ echo 'Fermé'; } else if($support->status == "REPLY") { echo 'Répondu'; }?></li>
                                    <li class="list-group-item">Sujet : <strong><?= $support->content ?></strong></li>
                                    <li class="list-group-item">Département : <strong><?= $support->department ?></strong></li>
                                    <li class="list-group-item">Dernière mise à jour : <strong><?= date('d/m/Y à H:i', strtotime($support->date_created)); ?></strong></li>
                                    <li class="list-group-item">Date : <strong><?= date('d/m/Y à H:i', strtotime($support->date_updated)); ?></strong></li>
                                </ul>
                            </div>
                        </div>
                        <form method="post">
                        <?php if($support->status == "OPEN" or $support->status == "REPLY"){?>
                            <button class="btn btn-danger btn-lg btn-block" type="submit" name="formClose">Fermé la demande d'assistance</button>
                            <br>
                        <?php }else{?>
                            <button class="btn btn-danger btn-lg btn-block" disabled="disabled" type="button">Ticket fermé</button>
                            <br>
                        <?php }?>
                        </form>
                        <br>

                    </div>

                    <div class="col-md-8">
                        <br>
                        <?= $support->getAllReplyTickets($params['id']) ?>
                        <div class="col-md-12">
                            <?php if($support->status == "CLOSE"){?>
                                <div class="alert alert-warning">
                                    <div class="alert-body">
                                        Cette demande est fermé. Vous pouvez ouvrir un ticket sur votre espace client.
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if($support->status == "REPLY"){?>
                            <form method="post">
                                <div class="outer required">
                                    <div class="form-group af-inner">
                                        <center><textarea style="width:100%; resize:vertical;" name="message" placeholder="Merci de détailler au maximum votre problème.
Ne donnez aucune information confidentielle (mots de passe, ...), nos techniciens ont toutes les informations nécessaires pour régler votre probléme." rows="8" cols="50" data-toggle="tooltip" class="form-control"></textarea></center>
                                        <br/>
                                        <input type="hidden" name="_token" value="<?php echo CSRF::token()?>">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="formReply"><i class="fa fa-reply"></i> Répondre</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php }?>
                    </div>
                </div>
            </main>

<?php require 'templates/layouts/footer.php'; ?>