<?php
use config\Config;
use services\FunkoService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/services/FunkoService.php';
require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/models/Funko.php';
$session = $sessionService = SessionService::getInstance();
$config = Config::getInstance();
$admin = $_GET['admin'] ?? null;
?>
<div class="container">
    <?php if (isset($admin)):?>
        <div class="alert alert-danger alert-dismissible fade show col-12 my-3" role="alert">
            <strong>Permiso Denegado: </strong> Usted no tiene permisos de acceso.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <div class="d-sm-flex align-items-center justify-content-between m-4 row">
        <h1 class="h3 mb-0 text-gray-800 col-4">
            <?php echo $session->getHomeMessage();
                $config = Config::getInstance();?>
        </h1>
        <span class="col-4"></span>
        <form
                action="index.php"
                class="d-none col-4 d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search"
                id="form-name"
                method="get">
            <div class="input-group">
                <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar por nombre..."
                       aria-label="search" id="search" name="search">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <?php
        $searchItem = $_GET['search'] ?? null;
        $searchCategory = $_GET['category'] ?? null;
        $funkosService = new FunkoService($config->db);
        $funkos = $funkosService->findAllByName($searchItem);
        $funkosByCategory = $funkosService->findAllByCategory($searchCategory);
        ?>
        <?php foreach ($searchCategory ? $funkosByCategory : $funkos as $funko) : ?>
        <div class="col-3 my-2">
            <div class="card border-primary">
                <img class="card-img-top" src="<?php echo htmlspecialchars($funko->img);?>" style="height: 200px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title text-primary"><?php echo htmlspecialchars($funko->name); ?></h5>
                    <p class="card-text text-success"><?php echo htmlspecialchars($funko->price);?></p>
                    <a href="details.php?id=<?php echo $funko->id; ?>" class="btn btn-secondary">Añadir al carrito</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>