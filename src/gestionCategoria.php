<?php
use config\Config;
use services\CategoriaService;
use services\SessionService;

require_once 'vendor/autoload.php';
require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/services/CategoriaService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/models/Categoria.php';
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
    <?php if (isset($create)):?>
        <div class="alert alert-<?= $create==="true" ? 'success' : 'danger' ?> alert-dismissible fade show col-12 my-3" role="alert">
            <?= $create==="true" ? "<strong>Operacion Exitosa: </strong> Se ha creado correctamente la Categoria." : "<strong>Operacion Fallida: </strong> No se ha podido crear la Categoria." ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($update)):?>
        <div class="alert alert-<?= $update==="true" ? 'success' : 'danger' ?> alert-dismissible fade show col-12 my-3" role="alert">
            <?= $update==="true" ? "<strong>Operacion Exitosa: </strong> Se ha actualizado correctamente la Categoria." : "<strong>Operacion Fallida: </strong> No se ha podido actualizar la Categoria." ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($delete)):?>
        <div class="alert alert-<?= $delete==="true" ? 'success' : 'danger' ?> alert-dismissible fade show col-12 my-3" role="alert">
            <?= $delete==="true" ? "<strong>Operacion Exitosa: </strong> Se ha eliminado correctamente la Categoria." : "<strong>Operacion Fallida: </strong> No se ha podido eliminar la Categoria." ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <!-- Page Heading -->
    <div class="mx-5">
        <h1 class="h3 mb-2 text-gray-800">Gestion de Categorias</h1>
        <p class="mb-4">Gestione las Categorias dentro del Sistema.</p>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow m-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary fs-4">Categorias</h6>
            <a class="btn btn-outline-warning" href="createCategoria.php">
            Crear Categoria
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <?php
                $categoriaService = new CategoriaService($config->db);
                $categorias = $categoriaService->findAll();
                ?>
                <table class="table table-bordered" id="dataTable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Categoria</th>
                        <th>Estado</th>
                        <th>Gestionar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categorias as $categoria) : ?>
                        <tr>
                            <td class="text-xl-center"><?php echo htmlspecialchars($categoria->id); ?></td>
                            <td class="text-success"><?php echo htmlspecialchars($categoria->nameCategory); ?></td>
                            <td class="bg-<?php echo htmlspecialchars($categoria->isDeleted ? 'danger-subtle' : 'success-subtle'); ?> text-center"><?php echo htmlspecialchars($categoria->isDeleted ? 'Inactivo' : 'Activo'); ?></td>
                            <td>
                                <a class="btn btn-danger btn-circle btn-sm" href="deleteCategoria.php?id=<?php echo $categoria->id; ?>">
                                    Eliminar
                                </a>
                                <a class="btn btn-primary btn-circle btn-sm" href="updateCategoria.php?id=<?php echo $categoria->id; ?>">
                                    Actualizar
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
<?php include 'footer.php'?>
<!-- jQuery (necesario para el funcionamiento de Bootstrap JavaScript) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>