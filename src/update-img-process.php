<?php

use config\Config;
use services\FunkoService;
use services\SessionService;
use Ramsey\Uuid\Uuid;

require_once 'vendor/autoload.php';

require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/services/FunkoService.php';
require_once __DIR__ . '/services/SessionService.php';
require_once __DIR__ . '/models/Funko.php';

$session = SessionService::getInstance();

if (!$session->isAdmin()) {
    echo "<script type='text/javascript'>
            window.location.href = 'index.php?admin=false';
          </script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $config = Config::getInstance();

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        $uploadDir = $config->uploadPath;

        $archivo = $_FILES['imagen'];

        $nombre = $archivo['name'];
        $tipo = $archivo['type'];
        $tmpPath = $archivo['tmp_name'];
        $error = $archivo['error'];

        $allowedTypes = ['image/jpeg', 'image/png'];
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedType = finfo_file($fileInfo, $tmpPath);
        $extension = strtolower(pathinfo($nombre, PATHINFO_EXTENSION));

        if (in_array($detectedType, $allowedTypes) && in_array($extension, $allowedExtensions)) {

            $funkoService = new FunkoService($config->db);
            $funko = $funkoService->findById($id);
            if ($funko === null) {
                header('Location: index.php');
                exit;
            }

            $newName = Uuid::uuid4()->toString() . '.' . $extension;


            move_uploaded_file($tmpPath, $uploadDir . $newName);

            $funko->img = $config->uploadUrl . $newName;

            $funkoService->update($funko);


            header('Location: gestion.php?update=true');
            exit;
        }
        header('Location: gestion.php?img=false');
        exit;
    }else{
        header('Location: gestion.php?img=false');
        exit;
    }
}