<?php
use src\User\Register;
use src\Helper\FlashService;
use src\Helper\CSRF;

$register = new Register();

if(isset($_POST['submitRegister'])) {
    $mail = htmlspecialchars(trim($_POST['email']));
    $password = sha1($_POST['password']);
    $passwordConfirm = sha1($_POST['passwordConfirm']);
    $passwordDecoded = htmlspecialchars($_POST['password']);

    $register->createUser($mail, $password, $passwordConfirm, $passwordDecoded);
}

?>

<?php require 'templates/layouts/header-auth.php'; ?>
<body>
<main>
    <div class="container">

        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="/" class="logo d-flex align-items-center w-auto">
                                <img src="/templates/assets/img/logo.png" alt="">
                                <span class="d-none d-lg-block">EagleCloud</span>
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Inscription</h5>
                                    <p class="text-center small">Créez votre compte en quelques secondes et commencez à déployer.</p>
                                </div>

                                <form class="row g-3 needs-validation" method="post">

                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label">Adresse email</label>
                                        <div class="input-group has-validation">
                                            <input type="email" name="email" class="form-control" id="mail" required>
                                            <div class="invalid-feedback">Entrez votre adresse e-mail</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Mot de passe</label>
                                        <input type="password" name="password" class="form-control" id="yourPassword" required>
                                        <div class="invalid-feedback">Entrez votre mot de passe</div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Confirmation mot de passe</label>
                                        <input type="password" name="passwordConfirm" class="form-control" id="yourPassword" required>
                                        <div class="invalid-feedback">Retapez votre mot de passe</div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
                                            <label class="form-check-label" for="acceptTerms">Je suis d'accord et j'accepte les <a href="#">termes et conditions</a></label>
                                            <div class="invalid-feedback">Vous devez accepter avant de soumettre.</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <?php FlashService::flash(); ?>
                                        <input type="hidden" name="_token" value="<?php echo CSRF::token() ?>">
                                        <button class="btn btn-primary w-100" type="submit" name="submitRegister">Créer mon compte</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0"><a href="/login">Retour à la page de connexion</a></p>
                                    </div>
                                </form>

                            </div>
                        </div>

                        <div class="credits">
                            Service édité par <a href="https://eaglecloud.fr">EagleCloud</a>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </div>
</main><!-- End #main -->

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="/templates/assets/vendor/apexcharts/apexcharts.min.js"></script>
<script src="/templates/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/templates/assets/vendor/chart.js/chart.min.js"></script>
<script src="/templates/assets/vendor/echarts/echarts.min.js"></script>
<script src="/templates/assets/vendor/quill/quill.min.js"></script>
<script src="/templates/assets/vendor/simple-datatables/simple-datatables.js"></script>
<script src="/templates/assets/vendor/tinymce/tinymce.min.js"></script>
<script src="/templates/assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="/templates/assets/js/main.js"></script>

</body>
</html>