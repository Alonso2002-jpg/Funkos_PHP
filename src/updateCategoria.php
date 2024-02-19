<?php

use config\Config;
use models\Categoria;
use services\CategoriaService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/models/Categoria.php';
require_once __DIR__ . '/services/CategoriaService.php';

$session = SessionService::getInstance();

if (!$session->isAdmin()) {
    echo "<script type='text/javascript'>
            window.location.href = 'index.php?admin=false';
          </script>";
    exit;
}

$config = Config::getInstance();
$categoriasService = new CategoriaService($config->db);

$cateId=-1;
$categoria = null;
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $cateId = filter_input(INPUT_GET, 'id');

    if (!$cateId){
        header('Location: /gestionCategoria.php?update=false');
        exit;
    }

    $categoria = $categoriasService->findById($cateId);
    if (!$categoria) {
        header('Location: /gestionCategoria.php?update=false');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cateId = filter_input(INPUT_POST, 'id');
    $name = strtoupper(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $deleted = filter_input(INPUT_POST, 'deleted');

    if (empty($name)) {
        $errores['name'] = 'El nombre es obligatorio.';
    }

    if (!$categoria){
        $categoria = $categoriasService->findById($cateId);
    }
    if (count($errores) === 0) {

        $categoriaUpd= new Categoria();
        $categoriaUpd-> id = $cateId;
        $categoriaUpd->nameCategory = $name;
        $categoriaUpd->isDeleted = isset($deleted);

        try {
            $categoriasService->update($categoriaUpd);
            echo "<script type='text/javascript'>
                window.location.href = 'gestionCategoria.php?update=true';
                </script>";
        } catch (Exception $e) {
            echo "<script type='text/javascript'>
                window.location.href = 'gestionCategoria.php?update=false';
                </script>";
        }
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

        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

        </nav>

        <div class="container my-2">
            <form class="row gy-2 gx-3 align-items-center" method="post">
                <div class="col-12 text-center bg-dark text-light">
                    <h2>Actualizar una Categoria</h2>
                </div>
                <input type="hidden" name="id" value="<?php echo $cateId?>">
                <div class="col-12 text-center bg-secondary">
                    <h3 class="text-light">Complete los datos</h3>
                </div>
                <div class="col-8">
                    <?php if(isset($errores['name'])):?>
                        <div class="alert alert-danger align-items-center mb-1 d-flex" role="alert" >
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                            <p class="mt-3 ml-4 text-2xl">
                                <?php echo $errores['name'] ?? '' ?>
                            </p>
                        </div>
                    <?php endif; ?>
                    <div class="input-group">
                        <div class="input-group-text">Nombre</div>
                        <input type="text" class="form-control" value="<?php echo $categoria->nameCategory; ?>" id="name" placeholder="Nombre de la Categoria" name="name">
                    </div>
                </div>
                <span class="col-2"></span>
                <div class="col-2">
                    <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                        <input type="checkbox" class="btn-check" id="deleted" name="deleted" autocomplete="off" <?php echo $categoria->isDeleted ? 'checked' : ''; ?>>
                        <label class="btn btn-outline-danger" for="deleted">Eliminado?</label>
                    </div>
                </div>
                <div class="col-12 mt-5 row">
                    <button type="submit" class="btn btn-primary col-4">Actualizar</button>
                    <div class="col-4"></div>
                    <a href="gestionCategoria.php" class="btn btn-secondary col-4">Volver</a>
                </div>
            </form>
        </div>
        <?php include 'footer.php'; ?>
        <!-- jQuery (necesario para el funcionamiento de Bootstrap JavaScript) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>