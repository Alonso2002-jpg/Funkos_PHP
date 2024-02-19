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

$funkoId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$funko = null;

if (!$funkoId) {
    header('Location: /gestion.php?details=false');
    exit;
}else{
    $config = Config::getInstance();
    $funkosService = new FunkoService($config->db);
    $funko = $funkosService->findById($funkoId);
    if (!$funko) {
        header('Location: /gestion.php?details=false');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $session->getTitle();?></title>
    <!-- Enlace a Bootstrap CSS -->
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'?>
<div class="container-fluid p-4 w-100">
        <div class="card">
            <div class="container-fliud">
                <a type="button" class="btn-close col-md-12"  href="gestion.php"></a>
                <div class="wrapper row">
                    <div class="preview col-md-6">
                        <div class="preview-pic tab-content">
                            <div class="tab-pane active m-auto" id="pic-1" style="height: 350px; width: 350px; object-fit: cover;"><img src="<?=$funko->img?>" /></div>
                        </div>
                    </div>
                    <div class="details col-md-6 m-auto">
                        <h3 class="product-title text-primary"><?=$funko->name?></h3>
                        <h4 class="price my-2">Precio Actual: <span class="text-success">$<?=$funko->price?></span></h4>
                        <p class="fs-5 my-2"><strong>Date prisa!</strong> Solo nos quedan <strong><?=$funko->quantity?> unidades!!</strong></p>
                        <h5 class="sizes my-2">CATEGORIA: <span class="text-warning"><?=$funko->categoryName?></span></h5>
                        <div class="action mt-4">
                            <button class="add-to-cart btn btn-secondary w-100" type="button">Añadir al carrito!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php'?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>