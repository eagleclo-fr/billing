<?php
use src\User\Session;
use src\User\User;
use src\Helper\CSRF;
use src\Helper\FlashService;

$session = new Session();
$user = new User();

$session->getSession();
$user->userInfo($session->userid);

if(isset($_POST['submitAccount'])){

    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);

    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $region = htmlspecialchars($_POST['region']);
    $country = htmlspecialchars($_POST['country']);
    $user->editUser($session->userid, $firstname, $lastname, $address, $city, $region, $country);
}

if(isset($_POST['submitPassword'])){
    $newPassword = htmlspecialchars($_POST['newPassword']);
    $renewPassword = htmlspecialchars($_POST['renewPassword']);
    $user->resetPassword($session->userid, $newPassword, $renewPassword);
}

?>
<?php require 'templates/layouts/header.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Compte</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/manager">Dashboard</a></li>
                    <li class="breadcrumb-item active">Compte</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            <img src="/templates/assets/img/user.png" alt="Profile" class="rounded-circle" width="80px" height="80px">
                            <h2><?= $user->firstname ?>, <?= $user->lastname ?></h2>
                            <h3>ID Client : <?= $user->customerid ?></h3>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview"><i class="bi bi-person"></i> Compte</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit"><i class="bi bi-pencil-square"></i> Éditer son compte</button>
                                </li>

                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password"><i class="bi bi-key"></i> Mot de passe</button>
                                </li>

                            </ul>
                            <div class="tab-content pt-2">

                                <?php FlashService::flash(); ?>

                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <h5 class="card-title">INFORMATIONS DU COMPTE</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label ">Prénom, Nom</div>
                                        <div class="col-lg-9 col-md-8"><?= $user->firstname ?>, <?= $user->lastname ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Adresse e-mail</div>
                                        <div class="col-lg-9 col-md-8"><?= $user->mail ?></div>
                                    </div>

                                    <h5 class="card-title">INFORMATIONS DE FACTURATION</h5>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Pays</div>
                                        <div class="col-lg-9 col-md-8"><?= $user->country ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Région</div>
                                        <div class="col-lg-9 col-md-8"><?= $user->region ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Adresse</div>
                                        <div class="col-lg-9 col-md-8"><?= $user->address ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Ville</div>
                                        <div class="col-lg-9 col-md-8"><?= $user->city ?></div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Date de créaton</div>
                                        <div class="col-lg-9 col-md-8"><?= $user->created_at ?></div>
                                    </div>

                                </div>

                                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                                    <!-- Profile Edit Form -->
                                    <form method="post">

                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Prénom</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="firstname" type="text" class="form-control" id="firstname" value="<?= $user->firstname ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Nom</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="lastname" type="text" class="form-control" id="lastname" value="<?= $user->lastname ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Adresse e-mail</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="email" type="text" class="form-control" id="email" value="<?= $user->mail ?>" disabled>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="Country" class="col-md-4 col-lg-3 col-form-label">Pays</label>
                                            <div class="col-md-8 col-lg-9">
                                                <select class="form-control" placeholder="Pays" name="country" id="country" required>
                                                    <option value="<?= $user->country ?>"><?= $user->country ?></option>
                                                    <option disabled>--</option>
                                                    <option value="France">France</option>
                                                    <option value="Belgique">Belgique</option>
                                                    <option value="Suisse">Angleterre</option>
                                                    <option value="Angleterre">Italie</option>
                                                    <option value="Allemagne">Allemagne</option>
                                                    <option value="Luxembourg">Luxembourg</option>
                                                    <option value="Espagne">Espagne</option>
                                                    <option value="Irlande">Irlande</option>
                                                    <option value="Hollande">Hollande</option>
                                                    <option value="Canada">Canada</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="Address" class="col-md-4 col-lg-3 col-form-label">Région</label>
                                            <div class="col-md-8 col-lg-9">
                                                <select class="form-control" placeholder="region" name="region" id="region" required>
                                                    <option value="<?= $user->region ?>"><?= $user->region ?></option>
                                                    <option disabled>--</option>
                                                    <option value="Ain">Ain</option>
                                                    <option value="Aisne">Aisne</option>
                                                    <option value="Allier">Allier</option>
                                                    <option value="Alpes-de-Haute-Provence">Alpes-de-Haute-Provence</option>
                                                    <option value="Hautes-Alpes">Hautes-Alpes</option>
                                                    <option value="Alpes-Maritimes">Alpes-Maritimes</option>
                                                    <option value="Ardèche">Ardèche</option>
                                                    <option value="Ardennes">Ardennes</option>
                                                    <option value="Ariège">Ariège</option>
                                                    <option value="Aube">Aube</option>
                                                    <option value="Aude">Aude</option>
                                                    <option value="Aveyron">Aveyron</option>
                                                    <option value="Bouches-du-Rhône">Bouches-du-Rhône</option>
                                                    <option value="Calvados">Calvados</option>
                                                    <option value="Cantal">Cantal</option>
                                                    <option value="Charente">Charente</option>
                                                    <option value="Charente-Maritime">Charente-Maritime</option>
                                                    <option value="Cher">Cher</option>
                                                    <option value="Corrèze">Corrèze</option>
                                                    <option value="Corse-du-Sud">Corse-du-Sud</option>
                                                    <option value="Haute-Corse">Haute-Corse</option>
                                                    <option value="Côte-D'Or">Côte-D'Or</option>
                                                    <option value="Côtes-d'Armor">Côtes-d'Armor</option>
                                                    <option value="Creuse">Creuse</option>
                                                    <option value="Dordogne">Dordogne</option>
                                                    <option value="Doubs">Doubs</option>
                                                    <option value="Drôme">Drôme</option>
                                                    <option value="Eure">Eure</option>
                                                    <option value="Eure-et-Loir">Eure-et-Loir</option>
                                                    <option value="Finistère">Finistère</option>
                                                    <option value="Gard">Gard</option>
                                                    <option value="Haute-Garonne">Haute-Garonne</option>
                                                    <option value="Gers">Gers</option>
                                                    <option value="Gironde">Gironde</option>
                                                    <option value="Hérault">Hérault</option>
                                                    <option value="Ille-et-Vilaine">Ille-et-Vilaine</option>
                                                    <option value="Indre">Indre</option>
                                                    <option value="Indre-et-Loire">Indre-et-Loire</option>
                                                    <option value="Isère">Isère</option>
                                                    <option value="Jura">Jura</option>
                                                    <option value="Landes">Landes</option>
                                                    <option value="Loire-et-Cher">Loire-et-Cher</option>
                                                    <option value="Loire">Loire</option>
                                                    <option value="Haute-Loire">Haute-Loire</option>
                                                    <option value="Loire-Atlantique">Loire-Atlantique</option>
                                                    <option value="Loiret">Loiret</option>
                                                    <option value="Lot">Lot</option>
                                                    <option value="Lot-et-Garonne">Lot-et-Garonne</option>
                                                    <option value="Lozère">Lozère</option>
                                                    <option value="Maine-et-Loire">Maine-et-Loire</option>
                                                    <option value="Manche">Manche</option>
                                                    <option value="Marne">Marne</option>
                                                    <option value="Haute-Marne">Haute-Marne</option>
                                                    <option value="Mayenne">Mayenne</option>
                                                    <option value="Meurthe-et-Moselle">Meurthe-et-Moselle</option>
                                                    <option value="Meuse">Meuse</option>
                                                    <option value="Morbihan">Morbihan</option>
                                                    <option value="Moselle">Moselle</option>
                                                    <option value="Nièvre">Nièvre</option>
                                                    <option value="Nord">Nord</option>
                                                    <option value="Oise">Oise</option>
                                                    <option value="Orne">Orne</option>
                                                    <option value="Pas-de-Calais">Pas-de-Calais</option>
                                                    <option value="Puy-de-Dôme">Puy-de-Dôme</option>
                                                    <option value="Pyrénées-Atlantiques">Pyrénées-Atlantiques</option>
                                                    <option value="Hautes-Pyrénées">Hautes-Pyrénées</option>
                                                    <option value="Pyrénées-Orientales">Pyrénées-Orientales</option>
                                                    <option value="Bas-Rhin">Bas-Rhin</option>
                                                    <option value="Haut-Rhin">Haut-Rhin</option>
                                                    <option value="Rhône">Rhône</option>
                                                    <option value="Haute-Saône">Haute-Saône</option>
                                                    <option value="Saône-et-Loire">Saône-et-Loire</option>
                                                    <option value="Sarthe">Sarthe</option>
                                                    <option value="Savoie">Savoie</option>
                                                    <option value="Haute-Savoie">Haute-Savoie</option>
                                                    <option value="Paris">Paris</option>
                                                    <option value="Seine-Maritime">Seine-Maritime</option>
                                                    <option value="Seine-et-Marne">Seine-et-Marne</option>
                                                    <option value="Yvelines">Yvelines</option>
                                                    <option value="Deux-Sèvres">Deux-Sèvres</option>
                                                    <option value="Somme">Somme</option>
                                                    <option value="Tarn">Tarn</option>
                                                    <option value="Tarn-et-Garonne">Tarn-et-Garonne</option>
                                                    <option value="Var">Var</option>
                                                    <option value="Vaucluse">Vaucluse</option>
                                                    <option value="Vendée">Vendée</option>
                                                    <option value="Vienne">Vienne</option>
                                                    <option value="Haute-Vienne">Haute-Vienne</option>
                                                    <option value="Vosges">Vosges</option>
                                                    <option value="Yonne">Yonne</option>
                                                    <option value="Territoire-de-Belfort">Territoire-de-Belfort</option>
                                                    <option value="Essonne">Essonne</option>
                                                    <option value="Hauts-de-Seine">Hauts-de-Seine</option>
                                                    <option value="Seine-Saint-Denis">Seine-Saint-Denis</option>
                                                    <option value="Val-de-Marne">Val-de-Marne</option>
                                                    <option value="Val-d'Oise">Val-d'Oise</option>
                                                    <option value="Guadeloupe">Guadeloupe</option>
                                                    <option value="Martinique">Martinique</option>
                                                    <option value="Guyane">Guyane</option>
                                                    <option value="La Réunion">La Réunion</option>
                                                    <option value="Mayotte">Mayotte</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="Email" class="col-md-4 col-lg-3 col-form-label">Ville</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="city" type="text" class="form-control" id="city" value="<?= $user->city ?>">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Adresse</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="address" type="text" class="form-control" id="address" value="<?= $user->address ?>">
                                            </div>
                                        </div>

                                        <input type="hidden" name="_token" value="<?php echo CSRF::token() ?>">

                                        <button type="submit" name="submitAccount" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Sauvegarder</button>
                                    </form><!-- End Profile Edit Form -->

                                </div>

                                <div class="tab-pane fade pt-3" id="profile-change-password">
                                    <!-- Change Password Form -->
                                    <form method="post">

                                        <div class="row mb-3">
                                            <label for="newPassword" class="col-md-4 col-lg-3 col-form-label">Mot de passe</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="newPassword" type="password" class="form-control" id="newPassword">
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <label for="renewPassword" class="col-md-4 col-lg-3 col-form-label">Confirmation de mot de passe</label>
                                            <div class="col-md-8 col-lg-9">
                                                <input name="renewPassword" type="password" class="form-control" id="renewPassword">
                                            </div>
                                        </div>

                                        <input type="hidden" name="_token" value="<?php echo CSRF::token() ?>">

                                        <button type="submit" name="submitPassword" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Changer le mot de passe</button>
                                    </form>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->



<?php require 'templates/layouts/footer.php'; ?>