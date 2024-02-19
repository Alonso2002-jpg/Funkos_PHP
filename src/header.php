<?php
use services\SessionService;

require_once 'vendor/autoload.php';
require_once __DIR__ . '/services/SessionService.php';
$session = $sessionService = SessionService::getInstance();
$uri = $_SERVER['REQUEST_URI'];
?>
<nav class="navbar navbar-expand-lg navbar-light bg-primary-subtle w-100">
    <div class="container">
        <a class="navbar-brand fs-2" href="index.php">Funkos Store</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item me-2">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary">Categorias</button>
                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Nuestras Categorias</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="index.php?category=serie">Serie</a></li>
                            <li><a class="dropdown-item" href="index.php?category=disney">Disney</a></li>
                            <li><a class="dropdown-item" href="index.php?category=super">Superheroes</a></li>
                            <li><a class="dropdown-item" href="index.php?category=peliculas">Peliculas</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="index.php?category=otros">Otros</a></li>
                        </ul>
                    </div>
                </li>
                <?php if ($session->isAdmin()): ?>
                <li class="nav-item mx-2">
                    <div class="btn-group">
                        <button type="button" class="btn btn-secondary">Gestion General</button>
                        <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="visually-hidden">Funciones</span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="gestionCategoria.php">Gestionar Categorias</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="gestion.php">Gestionar Funkos</a></li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link btn btn-light"
                    <?php
                    if ($session->isLoggedIn()) {
                        echo 'href="logout.php">Logout';
                    } else {
                        echo 'href="login.php">Login';
                    }
                    ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>