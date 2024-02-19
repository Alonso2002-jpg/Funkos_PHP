<?php
use services\SessionService;

require_once 'vendor/autoload.php';
require_once __DIR__ . '/services/SessionService.php';
$session = $sessionService = SessionService::getInstance();
?>
<footer class="navbar navbar-expand-lg navbar-light bg-primary-subtle bg-info-subtle mt-2 d-flex justify-content-center">
    <div class="ms-5">
        <p class="mt-4 text-center" style="font-size: smaller;">
            <?php
            if ($session->isLoggedIn()) {
                echo "<span>Nº de visitas: {$session->getVisitCount()}</span>";
                echo "<span>, desde el último login en: {$session->getLastLoginDate()}</span>";
            }
            ?>
        </p>
    </div>
    <div class="container align-center p-4">
        <p class="text-center m-auto text-muted">Copyright © 2023. Todos los Derechos Reservados.</p>
    </div>
</footer>