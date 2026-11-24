<?php

// Conexión con la base de datos
require_once 'includes/db.php';

// Título de la página
$titulo = 'Inicio';

// Consultar cantidad de videojuegos registrados
$total = $pdo->query(
    "SELECT COUNT(*) FROM videojuegos"
)->fetchColumn();

// Incluir encabezado
require_once 'includes/header.php';

?>

<!-- SECCIÓN PRINCIPAL -->

<section class="hero">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-7">

                <p class="text-purple fw-bold">
                    BIENVENIDO A GAMEZONE
                </p>

                <h1>
                    TU MUNDO DE
                    <span class="text-purple">VIDEOJUEGOS</span>
                </h1>

                <p class="muted fs-5 mt-3">
                    Administra, descubre y organiza tus videojuegos
                    favoritos en un solo lugar.
                </p>

                <div class="mt-4">

                    <a href="catalogo.php"
                       class="btn btn-purple btn-lg me-2 mb-2">

                        Explorar catálogo

                    </a>

                    <a href="formulario.php"
                       class="btn btn-outline-light btn-lg mb-2">

                        Agregar videojuego

                    </a>

                </div>

            </div>

            <div class="col-lg-5 mt-4 mt-lg-0">

                <img
                    src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=900"
                    class="img-fluid rounded-4"
                    alt="Espacio de videojuegos">

            </div>

        </div>

    </div>

</section>


<!-- SECCIÓN DE FUNCIONALIDADES -->

<section class="container py-5">

    <div class="text-center mb-5">

        <h2>
            ¿QUÉ PUEDES HACER EN
            <span class="text-purple">GAMEZONE?</span>
        </h2>

        <p class="muted">
            Administra tu colección de videojuegos fácilmente.
        </p>

    </div>


    <div class="row g-4">

        <!-- REGISTRAR -->

        <div class="col-md-4">

            <div class="game-card p-4">

                <h3>🎮 Registrar</h3>

                <p class="muted">
                    Agrega nuevos videojuegos a tu colección
                    y guarda toda su información.
                </p>

                <a href="formulario.php" class="btn btn-purple">
                    Registrar videojuego
                </a>

            </div>

        </div>


        <!-- CONSULTAR -->

        <div class="col-md-4">

            <div class="game-card p-4">

                <h3>🕹️ Consultar</h3>

                <p class="muted">
                    Explora todos los videojuegos registrados
                    en nuestro catálogo.
                </p>

                <a href="catalogo.php" class="btn btn-purple">
                    Ver catálogo
                </a>

            </div>

        </div>


        <!-- ADMINISTRAR -->

        <div class="col-md-4">

            <div class="game-card p-4">

                <h3>⚙️ Administrar</h3>

                <p class="muted">
                    Actualiza información o elimina videojuegos
                    de tu colección.
                </p>

                <a href="catalogo.php" class="btn btn-purple">
                    Administrar juegos
                </a>

            </div>

        </div>

    </div>

</section>


<!-- CONTADOR DE VIDEOJUEGOS -->

<section class="container py-5">

    <div class="game-card text-center p-5">

        <p class="text-purple fw-bold">
            NUESTRA COLECCIÓN
        </p>

        <h2>VIDEOJUEGOS REGISTRADOS</h2>

        <h1 class="display-1 fw-bold text-purple">

            <?= (int)$total ?>

        </h1>

        <p class="muted">
            Videojuegos almacenados en nuestra base de datos SQLite.
        </p>

        <a href="catalogo.php" class="btn btn-purple mt-3">

            Explorar colección

        </a>

    </div>

</section>


<?php

// Incluir pie de página
require_once 'includes/footer.php';

?>