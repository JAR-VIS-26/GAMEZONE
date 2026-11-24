<?php

// Conexión con SQLite
require_once 'includes/db.php';

$titulo = 'Catálogo';

// Consultar todos los videojuegos
$consulta = $pdo->query("
    SELECT *
    FROM videojuegos
    ORDER BY id DESC
");

$videojuegos = $consulta->fetchAll();

// Obtener todos los géneros individuales
$generos = [];

foreach ($videojuegos as $juego) {

    $listaGeneros = explode(',', $juego['genero']);

    foreach ($listaGeneros as $genero) {

        $genero = trim($genero);

        if ($genero !== '') {
            $generos[] = $genero;
        }

    }

}

// Eliminar géneros repetidos y ordenar alfabéticamente
$generos = array_unique($generos);
sort($generos);

// Incluir encabezado
require_once 'includes/header.php';

?>

<!-- ENCABEZADO DEL CATÁLOGO -->

<section class="container py-5">

    <div class="text-center mb-5">

        <p class="text-purple fw-bold">
            COLECCIÓN GAMEZONE
        </p>

        <h1>
            CATÁLOGO DE <span class="text-purple">VIDEOJUEGOS</span>
        </h1>

        <p class="muted">
            Explora y administra todos tus videojuegos.
        </p>

    </div>


    <!-- BOTÓN AGREGAR -->

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <h4>
            Videojuegos registrados:
            <span class="text-purple">
                <?= count($videojuegos) ?>
            </span>
        </h4>

        <a href="formulario.php" class="btn btn-purple">
            + Agregar videojuego
        </a>

    </div>


    <!-- BUSCADOR Y FILTRO -->

    <div class="row g-3 mb-5">

        <div class="col-md-8">

            <input
                type="text"
                id="busqueda"
                class="form-control"
                placeholder="Buscar videojuego por nombre..."
            >

        </div>

        <div class="col-md-4">

            <select id="filtroGenero" class="form-select">

                <option value="">
                    Todos los géneros
                </option>

                <?php foreach ($generos as $genero): ?>

                    <option value="<?= htmlspecialchars($genero, ENT_QUOTES, 'UTF-8') ?>">
                        <?= htmlspecialchars($genero, ENT_QUOTES, 'UTF-8') ?>
                    </option>

                <?php endforeach; ?>

            </select>

        </div>

    </div>


    <!-- LISTADO DE VIDEOJUEGOS -->

    <div class="row g-4" id="listaVideojuegos">

        <?php if (count($videojuegos) > 0): ?>

            <?php foreach ($videojuegos as $juego): ?>

                <div
                    class="col-sm-6 col-lg-4 juego"
                    data-nombre="<?= htmlspecialchars($juego['nombre'], ENT_QUOTES, 'UTF-8') ?>"
                    data-genero="<?= htmlspecialchars($juego['genero'], ENT_QUOTES, 'UTF-8') ?>"
                >

                    <div class="game-card">

                        <?php if (!empty($juego['imagen'])): ?>

                            <img
                                src="<?= htmlspecialchars($juego['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                                alt="<?= htmlspecialchars($juego['nombre']) ?>"
                            >

                        <?php else: ?>

                            <div class="d-flex align-items-center justify-content-center bg-dark"
                                 style="height:210px;">

                                <span class="display-4">🎮</span>

                            </div>

                        <?php endif; ?>


                        <div class="card-body">

                            <h5>
                                <?= htmlspecialchars($juego['nombre']) ?>
                            </h5>

                            <p class="muted mb-2">

                                Género:
                                <?= htmlspecialchars($juego['genero']) ?>

                            </p>

                            <p class="muted mb-2">

                                Plataforma:
                                <?= htmlspecialchars($juego['plataforma']) ?>

                            </p>

                            <p class="muted mb-3">

                                Año:
                                <?= (int)$juego['anio'] ?>

                            </p>

                            <h5 class="text-purple">

                                $<?= number_format((float)$juego['precio'], 2) ?>

                            </h5>


                            <!-- BOTONES -->

                            <div class="d-flex gap-2 flex-wrap mt-3">

                                <a
                                    href="detalle.php?id=<?= (int)$juego['id'] ?>"
                                    class="btn btn-outline-light btn-sm"
                                >
                                    Ver detalles
                                </a>

                                <a
                                    href="formulario.php?id=<?= (int)$juego['id'] ?>"
                                    class="btn btn-purple btn-sm"
                                >
                                    Editar
                                </a>

                                <form
                                    action="eliminar.php"
                                    method="POST"
                                    class="d-inline"
                                >

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= (int)$juego['id'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="btn btn-danger btn-sm"
                                    >
                                        Eliminar
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="col-12 text-center py-5">

                <h3>🎮 Todavía no hay videojuegos</h3>

                <p class="muted">
                    Tu catálogo está vacío. ¡Agrega tu primer videojuego!
                </p>

                <a href="formulario.php" class="btn btn-purple mt-3">

                    Agregar primer videojuego

                </a>

            </div>

        <?php endif; ?>


        <!-- MENSAJE SIN RESULTADOS -->

        <div id="sinResultados" class="col-12 text-center d-none">

            <h4>No se encontraron videojuegos.</h4>

            <p class="muted">
                Intenta buscar otro nombre o seleccionar otro género.
            </p>

        </div>

    </div>

</section>


<?php

require_once 'includes/footer.php';

?>