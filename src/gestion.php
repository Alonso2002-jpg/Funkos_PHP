<?php
use config\Config;
use services\FunkoService;
use services\SessionService;

require_once 'vendor/autoload.php';
require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/services/FunkoService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/models/Funko.php';
$config = Config::getInstance();
$session = $sessionService = SessionService::getInstance();

if (!$session->isAdmin()) {
    echo "<script type='text/javascript'>
            window.location.href = 'index.php?admin=false';
          </script>";
    exit;
}
$create = $_GET['create'] ?? null;
$update = $_GET['update'] ?? null;
$delete = $_GET['delete'] ?? null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $session->getTitle();?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'?>

<div class="container-fluid p-4 w-100">
    <?php if (isset($update)):?>
        <div class="alert alert-<?= $update==="true" ? 'success' : 'danger' ?> alert-dismissible fade show col-12 my-3" role="alert">
            <?= $update==="true" ? "<strong>Operacion Exitosa: </strong> Se ha actualizado correctamente el funko." : "<strong>Operacion Fallida: </strong> No se ha podido actualizar el funko." ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($delete)):?>
        <div class="alert alert-<?= $delete==="true" ? 'success' : 'danger' ?> alert-dismissible fade show col-12 my-3" role="alert">
            <?= $delete==="true" ? "<strong>Operacion Exitosa: </strong> Se ha eliminado correctamente el funko." : "<strong>Operacion Fallida: </strong> No se ha podido eliminar el funko." ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($create)):?>
        <div class="alert alert-<?= $create ? 'success' : 'danger' ?> alert-dismissible fade show col-12 my-3" role="alert">
            <?= $create ? "<strong>Operacion Exitosa: </strong> Se ha creado correctamente el funko." : "<strong>Operacion Fallida: </strong> No se ha podido crear el funko." ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">Gestion de Funkos</h1>
    <p class="mb-4">Gestione los Funkos dentro del Sistema.</p>

    <!-- DataTales Example -->
    <div class="card shadow m-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-danger fs-4">Funkos</h6>
            <a class="btn btn-outline-warning" href="create.php">
                Crear Funko
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <?php
                $searchItem = $_GET['name'] ?? null;
                $funkosService = new FunkoService($config->db);
                $funkos = $funkosService->findAllByCategory($searchItem);
                ?>
                <table class="table table-bordered" id="dataTable">
                    <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Categoria</th>
                        <th>Gestionar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($funkos as $funko) : ?>
                        <tr>
                            <td class="text-xl-center fs-5"><?php echo htmlspecialchars($funko->name); ?></td>
                            <td class="text-success"><?php echo htmlspecialchars($funko->price); ?></td>
                            <td><?php echo htmlspecialchars($funko->quantity); ?></td>
                            <td><?php echo htmlspecialchars($funko->categoryName); ?></td>
                            <td><img src="<?php echo htmlspecialchars($funko->img); ?>" style="height: 150px; width: 150px; object-fit: cover;" alt="" class="img-default"></td>
                            <td><a class="btn btn-danger btn-circle btn-sm" href="delete.php?id=<?php echo $funko->id; ?>">
                                    Eliminar
                                </a>
                                <a class="btn btn-info btn-circle btn-sm" href="update.php?id=<?php echo $funko->id; ?>">
                                    Actualizar
                                </a>
                                <a class="btn btn-primary btn-circle btn-sm" href="update-img.php?id=<?php echo $funko->id; ?>">
                                    Imagen
                                </a>
                                <a class="btn btn-success btn-circle btn-sm" href="details.php?id=<?php echo $funko->id; ?>">
                                    Detalles
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- jQuery (necesario para el funcionamiento de Bootstrap JavaScript) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>