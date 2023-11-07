<?php
use src\User\addons\PaginatedQuery;
use src\User\Session;
use src\User\User;
use src\Admin\UserAdmin;
use src\Helper\FlashService;

$session = new Session();
$user = new User();
$userAdmin = new UserAdmin();

$session->getSession();
$user->userInfo($session->userid);

$userAdmin->getUser($params['id']);

if(isset($_POST['submitAccount'])){

    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $mail = htmlspecialchars($_POST['mail']);

    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $region = htmlspecialchars($_POST['region']);
    $country = htmlspecialchars($_POST['country']);
    $credit = htmlspecialchars($_POST['credit']);
    $userAdmin->editUser($userAdmin->id, $firstname, $lastname, $address, $city, $region, $country, $credit, $mail);
}

?>
<?php require 'templates/layouts/header-admin.php'; ?>

    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Utilisateurs</h1>
            <h5>Administrez les différents utilisateurs du système.</h5>
        </div><!-- End Page Title -->
        <?php FlashService::flash(); ?>

        <div class="card">
            <div class="card-body">
                <br>
                <ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
                    <li class="nav-item" role="edit">
                        <a href="/admin/users/<?= $userAdmin->id ?>" class="nav-link active" aria-controls="edit" aria-selected="true"><i class="bi bi-pencil-square"></i> Édition du compte</a>
                    </li>
                    <li class="nav-item" role="identity">
                        <a href="/admin/users/<?= $userAdmin->id ?>/identity" class="nav-link" aria-controls="identity" aria-selected="false"><i class="bi bi-person-circle"></i> Informations du compte</a>
                    </li>
                    <li class="nav-item" role="security">
                        <a href="/admin/users/<?= $userAdmin->id ?>/security" class="nav-link" aria-controls="security" aria-selected="false"><i class="bi bi-key-fill"></i> Sécurité du compte</a>
                    </li>
                </ul>
            </div>
        </div>

        <form method="post">
        <div class="card">
            <div class="card-header">
                Informations personnelles
            </div>
            <div class="card-body">
                <br>
                <div class="row">
                    <div class="col-sm-6">
                        <label for="firstname">Prénom</label>
                        <input type="text" class="form-control" id="firstname" name="firstname" value="<?= $userAdmin->firstname ?>">
                    </div>
                    <div class="col-sm-6">
                        <label for="lastname">Nom de famille</label>
                        <input type="text" class="form-control" id="lastname" name="lastname" value="<?= $userAdmin->lastname ?>">
                    </div>
                    <br><br><br>
                    <div class="col-sm-8">
                        <label for="mail">Adresse E-Mail</label>
                        <input type="text" class="form-control" id="mail" name="mail" value="<?= $userAdmin->mail ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                Adresse de facturation
            </div>
            <div class="card-body">
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <label for="address">Adresse</label>
                        <input type="text" class="form-control" id="address" name="address" value="<?= $userAdmin->address ?>">
                    </div>
                    <br><br><br>
                    <div class="col-sm-6">
                        <label for="city">Ville</label>
                        <input type="text" class="form-control" id="city" name="city" value="<?= $userAdmin->city ?>">
                    </div>
                    <div class="col-sm-3">
                        <label for="region">État</label>
                        <input type="text" class="form-control" id="region" name="region" value="<?= $userAdmin->region ?>">
                    </div>
                    <div class="col-sm-3">
                        <label for="country">Pays</label>
                        <select class="form-control" placeholder="Pays" name="country" id="country">
                            <option value="<?= $userAdmin->country ?>"><?= $userAdmin->country ?></option>
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

                    <br><br><br>

                    <div class="col-sm-4">
                        <label class="sr-only" for="credit">Portefeuille</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="bi bi-currency-euro"></i></div>
                            </div>
                            <input type="text" name="credit" class="form-control" id="credit" value="<?= $userAdmin->solde ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <br>
            <div class="card-body">
                <div class="row">
                <div class="col-sm-4">
                <button type="submit" name="submitAccount" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Sauvegarder</button>
                </div>
                </div>
            </div>
        </div>
        </form>
    </main><!-- End #main -->

<?php require 'templates/layouts/footer.php'; ?>