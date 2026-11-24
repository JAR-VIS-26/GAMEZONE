<?php
if (!isset($titulo)) {
    $titulo = 'GameZone';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($titulo) ?> | GameZone</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-gamezone">
    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            🎮 GAMEZONE
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#menuPrincipal"
                aria-controls="menuPrincipal"
                aria-expanded="false"
                aria-label="Abrir menú">

            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Inicio</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="catalogo.php">Catálogo</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="formulario.php">Agregar videojuego</a>
                </li>

            </ul>

        </div>
    </div>
</nav>

<main>