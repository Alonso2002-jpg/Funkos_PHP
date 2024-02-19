<?php

use config\Config;
use models\Funko;
use services\CategoriaService;
use services\FunkoService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkoService.php';
require_once __DIR__ . '/models/Funko.php';
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
$funkosService = new FunkoService($config->db);

$categorias = $categoriasService->findAll();
$errores = [];
$funko = null;
$funkoId = -1;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $funkoId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if (!$funkoId){
        header('Location: /gestion.php?update=false');
        exit;
    }

        $funko = $funkosService->findById($funkoId);
    if (!$funko) {
        header('Location: /gestion.php?update=false');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $price = filter_input(INPUT_POST, 'price',FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);;
    $quantity = filter_input(INPUT_POST, 'quantity',  FILTER_SANITIZE_NUMBER_INT);
    $categoria = filter_input(INPUT_POST, 'category',  FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $funkoId = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    if (!$funko){
        $funko = $funkosService->findById($funkoId);
    }

    $categoria = $categoriasService->findByName($categoria);

    if (empty($name)) {
        $errores['name'] = 'El nombre es obligatorio.';
    }

    if (!isset($price) || $price === '') {
        $errores['price'] = 'El precio es obligatorio.';
    } elseif ($price < 0) {
        $errores['price'] = 'El precio no puede ser negativo.';
    }

    if (!isset($quantity) || $quantity === '') {
        $errores['quantity'] = 'La Cantidad es obligatorio.';
    } elseif ($quantity < 0) {
        $errores['quantity'] = 'La Cantidad no puede ser negativo.';
    }

    if (empty($categoria)) {
        $errores['category'] = 'La categoría es obligatoria.';
    }

    if (count($errores) === 0) {
        $funko = new Funko();
        $funko->id = $funkoId;
        $funko->name = $name;
        $funko->categoryName = $categoria->nameCategory;
        $funko->price = $price;
        $funko->quantity = $quantity;
        $funko->categoryId = $categoria->id;
        try {
            $funkosService->update($funko);
            echo "<script type='text/javascript'>
                window.location.href = 'gestion.php?update=true';
                </script>";
        } catch (Exception $e) {
            echo "<script type='text/javascript'>
                window.location.href = 'gestion.php?update=false';
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

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

        </nav>

        <div class="container my-2">
            <form class="row gy-2 gx-3 align-items-center" action="update.php" method="post">
                <input type="hidden" name="id" value="<?php echo $funkoId; ?>">
                <div class="col-12 text-center bg-dark text-light">
                    <h2>Actualizar Funkos</h2>
                </div>
                <div class="col-12 text-center bg-secondary">
                    <h3 class="text-light">Datos</h3>
                </div>
                <div class="col-12 my-3">
                    <?php if(isset($errores['name'])):?>
                    <div class="alert alert-danger align-items-center mb-3 d-flex" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        <p class="mt-3 ml-4 text-2xl">
                            <?php echo $errores['name']; ?>
                        </p>
                    </div>
                    <?php endif;?>
                    <div class="input-group">
                        <div class="input-group-text">Nombre</div>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Nombre del Funko" value="<?php echo $funko->name; ?>">
                    </div>
                </div>
                <div class="col-6 my-3">
                    <?php if(isset($errores['price'])):?>
                    <div class="alert alert-danger align-items-center mb-3 d-flex" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        <p class="mt-3 ml-4 text-2xl">
                            <?php echo $errores['price']; ?>
                        </p>
                    </div>
                    <?php endif;?>
                    <div class="input-group">
                        <div class="input-group-text">Precio</div>
                        <input type="number" name="price" class="form-control" step="any" id="price" placeholder="Precio Unitario" value="<?php echo $funko->price; ?>">
                    </div>
                </div>
                <div class="col-6 my-3">
                    <?php if(isset($errores['quantity'])):?>
                    <div class="alert alert-danger align-items-center mb-3 d-flex" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        <p class="mt-3 ml-4 text-2xl">
                            <?php echo $errores['quantity']; ?>
                        </p>
                    </div>
                    <?php endif;?>
                    <div class="input-group">
                        <div class="input-group-text">Cantidad</div>
                        <input type="number" name="quantity" class="form-control" id="quantity" placeholder="Cantidad" value="<?php echo $funko->quantity; ?>">
                    </div>
                </div>

                <div class="col-4">
                    <?php if(isset($errores['category'])):?>
                    <div class="alert alert-danger align-items-center mb-3 d-flex" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        <p class="mt-3 ml-4 text-2xl">
                            <?php echo $errores['category']; ?>
                        </p>
                    </div>
                    <?php endif;?>
                    <p class="font-monospace">Categoria</p>
                    <select class="form-select w-100" aria-label="select example" id="category" name="category">
                        <?php foreach ($categorias as $categoria): ?>
                             <option value="<?php echo $categoria->nameCategory; ?>" <?= $categoria->nameCategory == $funko->categoryName ? 'selected' : '' ?>> <?php echo $categoria->nameCategory; ?> </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-8">

                </div>
                <div class="col-12 mt-5 row">
                    <button type="submit" class="btn btn-primary col-4">Enviar</button>
                    <div class="col-4"></div>
                    <a type="reset" class="btn btn-secondary col-4" href="gestion.php">Cancelar</a>
                </div>
            </form>
        </div>

    </div>
</body>
</html>