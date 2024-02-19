<?php


use config\Config;
use services\SessionService;
use services\UserService;


require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/config/Config.php';

$session = SessionService::getInstance();
$config = Config::getInstance();

$error = '';
$usersService = new UserService($config->db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'contraseña', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$username || !$password) {
        $error = 'Usuario/a o contraseña inválidos.';
    } else {
        try {
            $user = $usersService->authenticate($username, $password);
            if ($user) {
                $isAdmin = in_array('ADMIN', $user->roles);
                $session->login($user->username, $isAdmin);
                header('Location: index.php');
                exit;
            } else {
                $error = 'Usuario/a o contraseña inválidos.';
            }
        } catch (Exception $e) {
            $error = 'Error en el sistema. Por favor intente más tarde.';
        }
    }
}
?>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $session->getTitle();?></title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center m-auto">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Inicia Sesion!</h1>
                                </div>
                                <?php if (!empty($error)):?>
                                    <div class="alert alert-danger alert-dismissible fade show col-12 my-3" role="alert">
                                        <strong>ERROR: </strong> <?php echo $error; ?>
                                    </div>
                                <?php endif; ?>
                                <form class="user" action="login.php" method="post">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user"
                                               id="username" name="username" aria-describedby="emailHelp"
                                               placeholder="Ingrese su nombre de usuario..">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                               id="contraseña" name="contraseña" placeholder="Contraseña">
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Ingresar
                                    </button>
                                    <hr>
                                </form>
                                <hr>
                                <div class="text-center my-5">
                                    <a class="medium" href="register.php">Quiero registrarme!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

</body>

</html>