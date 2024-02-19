<?php

use config\Config;
use models\Funko;
use services\FunkoService;
use services\SessionService;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkoService.php';
require_once __DIR__ . '/models/Funko.php';
require_once __DIR__ . '/services/SessionService.php';

$session = SessionService::getInstance();

if (!$session->isAdmin()) {
    echo "<script type='text/javascript'>
            window.location.href = 'index.php?admin=false';
          </script>";
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$funko = null;

if (!$id) {
    header('Location: gestion.php?delete=false');
    exit;
} else {


        $config = Config::getInstance();
        $funkosService = new FunkoService($config->db);
        $funko = $funkosService->findById($id);
        if ($funko) {
            if ($funko->img !== Funko::$IMAGEN_DEFAULT) {
                $imageUrl = $funko->img;
                $basePath = $config->uploadPath;
                $imagePathInUrl = parse_url($imageUrl, PHP_URL_PATH);
                $imageFile = basename($imagePathInUrl);
                $imageFilePath = $basePath . $imageFile;

                if (file_exists($imageFilePath)) {
                    unlink($imageFilePath);
                }
            }
            try {
                $funkosService->deleteById($id);
                echo "<script type='text/javascript'>
                window.location.href = 'gestion.php?delete=true';
                </script>";
            }catch (Exception $e) {
                header('Location: gestion.php?delete=false');
                exit;
            }
        }else{
            header('Location: gestion.php?delete=false');
            exit;
        }

}