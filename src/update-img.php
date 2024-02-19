<?php


use config\Config;
use services\FunkoService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkoService.php';
require_once __DIR__ . '/models/Funko.php';
$session = SessionService::getInstance();

if (!$session->isAdmin()) {
    echo "<script type='text/javascript'>
            window.location.href = 'index.php?admin=false';
          </script>";
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$funko = null;

if ($id === false) {
    header('Location: index.php');
    exit;
} else {
    $config = Config::getInstance();
    $funkoService = new FunkoService($config->db);
    $funko = $funkoService->findById($id);
    if ($funko === null) {
        echo "<script type='text/javascript'>
                window.location.href = 'gestion.php?img=false';
                </script>";
    }
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $session->getTitle();?></title>
    <!-- Enlace a Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'?>
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

        </nav>

        <div class="container my-2">
            <form class="row gy-2 gx-3 align-items-center" action="update-img-process.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <div class="col-12 text-center bg-dark text-light">
                    <h2>Actualizar Imagen</h2>
                </div>
                <div class="col-12 text-center bg-secondary">
                    <h3 class="text-light">Datos</h3>
                </div>
                <div class="col-12 my-3">
                    <div class="input-group">
                        <div class="input-group-text">Nombre</div>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Nombre del Funko" value="<?php echo $funko->name; ?>" disabled>
                    </div>
                </div>

                <div class="col-4">
                    <p class="font-monospace">Imagen</p>
                    <img src="<?= $funko->img;?>" class="card-img-top img-def" alt="Funko Prueba">
                </div>
                <div class="col-4 text-center bg-secondary">

                </div>
                <div class="col-4 mb-3">
                    <input class="form-control" type="file" id="imagen" required name="imagen">
                </div>
                <div class="col-12 mt-5 row">
                    <button type="submit" class="btn btn-primary col-4">Enviar</button>
                    <div class="col-4"></div>
                    <a type="reset" class="btn btn-secondary col-4" href="gestion.php">Volver</a>
                </div>
            </form>
        </div>

    </div>
</body>
</html>