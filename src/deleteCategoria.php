<?php

use config\Config;
use models\Categoria;
use services\CategoriaService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/CategoriaService.php';
require_once __DIR__ . '/models/Categoria.php';
require_once __DIR__ . '/services/SessionService.php';

$session = SessionService::getInstance();

if (!$session->isAdmin()) {
    echo "<script type='text/javascript'>
            window.location.href = 'index.php?admin=false';
          </script>";
    exit;
}

$id = filter_input(INPUT_GET, 'id');
$categoria = null;

if (!$id) {
    header('Location: gestionCategoria.php?delete=false');
    exit;
} else {


    $config = Config::getInstance();
    $categoriaService = new CategoriaService($config->db);
    $categoria = $categoriaService->findById($id);
    if ($categoria) {
        try {
            $categoriaService->deleteById($id);
            echo "<script type='text/javascript'>
                window.location.href = 'gestionCategoria.php?delete=true';
                </script>";
        }catch (Exception $e) {
            header('Location: gestionCategoria.php?delete=false');
            exit;
        }
    }else{
        header('Location: gestionCategoria.php?delete=false');
        exit;
    }
}