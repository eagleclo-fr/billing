<?php
use src\User\Login;
use src\Helper\FlashService;
use src\User\Session;
use src\Helper\CSRF;

$login = new Login();
$session = new Session();

$session->getActiveSession();

if(isset($_POST['submitLogin'])) {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = sha1($_POST['password']);
    $login->connectUser($email, $password);
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
                                    <h5 class="card-title text-center pb-0 fs-4">Connexion à votre compte</h5>
                                    <p class="text-center small">Entrez votre adresse email ainsi que votre mot de passe pour vous connecter.</p>
                                </div>

                                <form class="row g-3 needs-validation" method="post">

                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label">Identifiant</label>
                                        <div class="input-group has-validation">
                                            <input type="email" name="email" class="form-control" id="email" required>
                                            <div class="invalid-feedback">Entrez votre email</div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="yourPassword" class="form-label">Password</label>
                                        <input type="password" name="password" class="form-control" id="password" required>
                                        <div class="invalid-feedback">Entrez votre mot de passe</div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Se souvenir de moi</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <?php FlashService::flash(); ?>
                                        <input type="hidden" name="_token" value="<?php echo CSRF::token() ?>">
                                        <button class="btn btn-primary w-100" type="submit" name="submitLogin">Se connecter</button>
                                    </div>
                                    <div class="col-12">
                                        <p class="small mb-0">Pas encore inscrit ? <a href="/register">Créer un compte</a></p>
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