<?php

// Conexión con SQLite
require_once 'includes/db.php';

$titulo = 'Detalle del videojuego';

// Obtener el ID desde la URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Verificar que el ID sea válido
if (!$id || $id < 1) {
    header('Location: catalogo.php');
    exit;
}

// Consultar videojuego
$consulta = $pdo->prepare(
    "SELECT * FROM videojuegos WHERE id = :id"
);

$consulta->execute([
    ':id' => $id
]);

$juego = $consulta->fetch();

// Verificar que exista
if (!$juego) {
    http_response_code(404);
    exit('El videojuego que buscas no existe.');
}

// Incluir encabezado
require_once 'includes/header.php';

?>

<!-- DETALLE DEL VIDEOJUEGO -->

<section class="container py-5">

    <div class="mb-4">

        <a href="catalogo.php" class="btn btn-outline-light">
            ← Volver al catálogo
        </a>

    </div>


    <div class="row g-5 align-items-start">

        <!-- IMAGEN -->

        <div class="col-lg-5">

            <?php if (!empty($juego['imagen'])): ?>

                <img
                    src="<?= htmlspecialchars($juego['imagen'], ENT_QUOTES, 'UTF-8') ?>"
                    alt="<?= htmlspecialchars($juego['nombre']) ?>"
                    class="game-cover"
                >

            <?php else: ?>

                <div class="game-card d-flex align-items-center justify-content-center"
                     style="height:400px;">

                    <span class="display-1">🎮</span>

                </div>

            <?php endif; ?>

        </div>


        <!-- INFORMACIÓN -->

        <div class="col-lg-7">

            <p class="text-purple fw-bold">
                INFORMACIÓN DEL VIDEOJUEGO
            </p>

            <h1 class="mb-3">
                <?= htmlspecialchars($juego['nombre']) ?>
            </h1>

            <h3 class="text-purple mb-4">
                $<?= number_format((float)$juego['precio'], 2) ?>
            </h3>


            <div class="game-card p-4 mb-4">

                <h4 class="mb-4">
                    Detalles generales
                </h4>

                <p>
                    <strong>Género:</strong>
                    <span class="muted">
                        <?= htmlspecialchars($juego['genero']) ?>
                    </span>
                </p>

                <p>
                    <strong>Plataforma:</strong>
                    <span class="muted">
                        <?= htmlspecialchars($juego['plataforma']) ?>
                    </span>
                </p>

                <p>
                    <strong>Desarrollador:</strong>
                    <span class="muted">
                        <?= htmlspecialchars($juego['desarrollador']) ?>
                    </span>
                </p>

                <p>
                    <strong>Año de lanzamiento:</strong>
                    <span class="muted">
                        <?= (int)$juego['anio'] ?>
                    </span>
                </p>

                <p class="mb-0">
                    <strong>ID del videojuego:</strong>
                    <span class="muted">
                        <?= (int)$juego['id'] ?>
                    </span>
                </p>

            </div>


            <!-- DESCRIPCIÓN -->

            <div class="game-card p-4 mb-4">

                <h4 class="mb-3">
                    Descripción
                </h4>

                <p class="muted mb-0">

                    <?= !empty($juego['descripcion'])
                        ? nl2br(htmlspecialchars($juego['descripcion']))
                        : 'No hay una descripción registrada para este videojuego.' ?>

                </p>

            </div>


            <!-- BOTONES -->

            <div class="d-flex gap-2 flex-wrap">

                <a
                    href="formulario.php?id=<?= (int)$juego['id'] ?>"
                    class="btn btn-purple"
                >
                    Editar videojuego
                </a>

                <form action="eliminar.php" method="POST">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= (int)$juego['id'] ?>"
                    >

                    <button type="submit" class="btn btn-danger">
                        Eliminar videojuego
                    </button>

                </form>

            </div>

        </div>

    </div>

</section>


<?php

// Incluir pie de página
require_once 'includes/footer.php';

?>