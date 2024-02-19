<?php

use config\Config;
use services\SessionService;
use services\UserService;
use models\User;


require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/services/UserService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/models/User.php';

$session = SessionService::getInstance();
$config = Config::getInstance();

$error = '';
$usersService = new UserService($config->db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $lastname = filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $repPassword = filter_input(INPUT_POST, 'repPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (!$username || !$password || !$repPassword || !$name || !$lastname || !$email) {
        echo "<script>alert('Todos los campos son obligatorios.')</script>";
        $error = 'Datos inválidos.';
    } else {
        if ($password !== $repPassword) {
            $error = 'Las contraseñas no coinciden.';
        }else{
            try {
                $user = new User();
                $user->name = $name;
                $user->lastname = $lastname;
                $user->email = $email;
                $user->username = $username;
                $user->password = $password;

                $usersService->save($user);

                header('Location: login.php');
                exit;
            } catch (Exception $e) {
                $error = 'Error en el sistema. Por favor intente más tarde.';
            }
        }
    }
}
?>
<!DOCTYPE html>
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

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create una cuenta!</h1>
                        </div>
                        <?php if (!empty($error)):?>
                            <div class="alert alert-danger alert-dismissible fade show col-12 my-3" role="alert">
                                <strong>ERROR: </strong> <?php echo $error; ?>
                            </div>
                        <?php endif; ?>
                        <form class="user" method="post" action="register.php">
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="nombre" name="nombre"
                                           placeholder="Nombres">
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user" id="apellidos" name="apellidos"
                                           placeholder="Apellidos">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="txt" class="form-control form-control-user" id="username" name="username"
                                       placeholder="Username">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="email" name="email"
                                       placeholder="Correo Electronico">
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user"
                                           id="password" name="password" placeholder="Contraseña">
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user"
                                           id="repPassword" name="repPassword" placeholder="Repita contraseña">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Registrame
                            </button>
                            <hr>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="medium" href="login.php">Ya tienes una cuenta? Inicia Sesion!</a>
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