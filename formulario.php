<?php

require_once 'includes/db.php';

// Opciones disponibles
$listaGeneros = [
    'Acción',
    'Aventura',
    'Deportes',
    'Carreras',
    'RPG',
    'Terror',
    'Estrategia',
    'Shooter',
    'Simulación',
    'Otro'
];

$plataformas = [
    'PC',
    'PlayStation 5',
    'PlayStation 4',
    'Xbox Series X/S',
    'Xbox One',
    'Nintendo Switch',
    'Otra'
];

// Determinar si estamos editando
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

$editando = $id !== null && $id !== false && $id > 0;

// Valores iniciales
$juego = [
    'nombre' => '',
    'genero' => [],
    'plataforma' => [],
    'desarrollador' => '',
    'anio' => '',
    'precio' => '',
    'imagen' => '',
    'descripcion' => ''
];

$errores = [];

// Consultar videojuego si estamos editando
if ($editando) {

    $consulta = $pdo->prepare(
        "SELECT * FROM videojuegos WHERE id = :id"
    );

    $consulta->execute([':id' => $id]);

    $registro = $consulta->fetch();

    if (!$registro) {
        http_response_code(404);
        exit('Videojuego no encontrado.');
    }

    $juego = $registro;

    // Convertir géneros y plataformas en arreglos
    $juego['genero'] = !empty($registro['genero'])
        ? explode(', ', $registro['genero'])
        : [];

    $juego['plataforma'] = !empty($registro['plataforma'])
        ? explode(', ', $registro['plataforma'])
        : [];
}

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $juego['nombre'] = trim($_POST['nombre'] ?? '');

    $juego['genero'] = $_POST['genero'] ?? [];

    $juego['plataforma'] = $_POST['plataforma'] ?? [];

    if (!is_array($juego['genero'])) {
        $juego['genero'] = [];
    }

    if (!is_array($juego['plataforma'])) {
        $juego['plataforma'] = [];
    }

    $juego['desarrollador'] = trim($_POST['desarrollador'] ?? '');
    $juego['anio'] = trim($_POST['anio'] ?? '');
    $juego['precio'] = trim($_POST['precio'] ?? '');
    $juego['imagen'] = trim($_POST['imagen'] ?? '');
    $juego['descripcion'] = trim($_POST['descripcion'] ?? '');

    // Eliminar opciones repetidas
    $juego['genero'] = array_unique($juego['genero']);
    $juego['plataforma'] = array_unique($juego['plataforma']);

    // Validar nombre
    if ($juego['nombre'] === '') {
        $errores[] = 'El nombre es obligatorio.';
    }

    // Validar géneros
    if (empty($juego['genero'])) {
        $errores[] = 'Selecciona al menos un género.';
    }

    foreach ($juego['genero'] as $genero) {
        if (!in_array($genero, $listaGeneros, true)) {
            $errores[] = 'Selecciona géneros válidos.';
            break;
        }
    }

    // Validar plataformas
    if (empty($juego['plataforma'])) {
        $errores[] = 'Selecciona al menos una plataforma.';
    }

    foreach ($juego['plataforma'] as $plataforma) {
        if (!in_array($plataforma, $plataformas, true)) {
            $errores[] = 'Selecciona plataformas válidas.';
            break;
        }
    }

    // Validar desarrollador
    if ($juego['desarrollador'] === '') {
        $errores[] = 'El desarrollador es obligatorio.';
    }

    // Validar año
    $anioActual = (int) date('Y');

    if (
        filter_var($juego['anio'], FILTER_VALIDATE_INT) === false ||
        (int)$juego['anio'] < 1950 ||
        (int)$juego['anio'] > $anioActual
    ) {
        $errores[] = 'Ingresa un año válido.';
    }

    // Validar precio
    if (
        !is_numeric($juego['precio']) ||
        (float)$juego['precio'] < 0
    ) {
        $errores[] = 'Ingresa un precio válido.';
    }

    // Validar URL de imagen
    if (
        $juego['imagen'] !== '' &&
        filter_var($juego['imagen'], FILTER_VALIDATE_URL) === false
    ) {
        $errores[] = 'La URL de la imagen no es válida.';
    }

    // Guardar si no existen errores
    if (empty($errores)) {

        if ($editando) {

            // UPDATE
            $sql = "
                UPDATE videojuegos SET
                    nombre = :nombre,
                    genero = :genero,
                    plataforma = :plataforma,
                    desarrollador = :desarrollador,
                    anio = :anio,
                    precio = :precio,
                    imagen = :imagen,
                    descripcion = :descripcion
                WHERE id = :id
            ";

        } else {

            // CREATE
            $sql = "
                INSERT INTO videojuegos (
                    nombre,
                    genero,
                    plataforma,
                    desarrollador,
                    anio,
                    precio,
                    imagen,
                    descripcion
                ) VALUES (
                    :nombre,
                    :genero,
                    :plataforma,
                    :desarrollador,
                    :anio,
                    :precio,
                    :imagen,
                    :descripcion
                )
            ";

        }

        $consulta = $pdo->prepare($sql);

        $datos = [
            ':nombre' => $juego['nombre'],
            ':genero' => implode(', ', $juego['genero']),
            ':plataforma' => implode(', ', $juego['plataforma']),
            ':desarrollador' => $juego['desarrollador'],
            ':anio' => (int)$juego['anio'],
            ':precio' => (float)$juego['precio'],
            ':imagen' => $juego['imagen'],
            ':descripcion' => $juego['descripcion']
        ];

        if ($editando) {
            $datos[':id'] = $id;
        }

        $consulta->execute($datos);

        header('Location: catalogo.php');
        exit;

    }

}

$titulo = $editando ? 'Editar videojuego' : 'Agregar videojuego';

require_once 'includes/header.php';

?>

<style>
    .opciones-grupo {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        margin-top: 10px;
    }

    .opcion-circular {
        display: flex;
        align-items: center;
        gap: 10px;
        background-color: #25273b;
        border: 1px solid #34364d;
        border-radius: 8px;
        padding: 12px;
        cursor: pointer;
        color: white;
        transition: 0.2s;
    }

    .opcion-circular:hover {
        border-color: #8b5cf6;
    }

    .opcion-circular input {
        appearance: none;
        -webkit-appearance: none;
        width: 19px;
        height: 19px;
        min-width: 19px;
        border: 2px solid #b7b8ca;
        border-radius: 50%;
        cursor: pointer;
        position: relative;
    }

    .opcion-circular input:checked {
        border-color: #8b5cf6;
        background-color: #8b5cf6;
        box-shadow: inset 0 0 0 4px #25273b;
    }

    .opcion-circular:has(input:checked) {
        border-color: #8b5cf6;
        background-color: #302746;
    }

    @media (max-width: 576px) {
        .opciones-grupo {
            grid-template-columns: 1fr;
        }
    }
</style>

<section class="container py-5">

    <div class="text-center mb-5">

        <p class="text-purple fw-bold">GAMEZONE</p>

        <h1>
            <?= $editando ? 'EDITAR' : 'AGREGAR' ?>
            <span class="text-purple">VIDEOJUEGO</span>
        </h1>

        <p class="muted">
            <?= $editando
                ? 'Actualiza la información de tu videojuego.'
                : 'Completa el formulario para registrar un videojuego.' ?>
        </p>

    </div>

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="form-panel">

                <?php if (!empty($errores)): ?>

                    <div class="alert alert-danger">

                        <strong>Revisa los siguientes errores:</strong>

                        <ul class="mb-0 mt-2">

                            <?php foreach ($errores as $error): ?>

                                <li><?= htmlspecialchars($error) ?></li>

                            <?php endforeach; ?>

                        </ul>

                    </div>

                <?php endif; ?>

                <form
                    action="<?= $editando
                        ? 'formulario.php?id=' . $id
                        : 'formulario.php' ?>"
                    method="POST"
                    id="formularioJuego"
                >

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Nombre del videojuego *
                            </label>

                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                placeholder="Ej. God of War"
                                maxlength="150"
                                value="<?= htmlspecialchars($juego['nombre']) ?>"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Desarrollador *
                            </label>

                            <input
                                type="text"
                                name="desarrollador"
                                class="form-control"
                                placeholder="Ej. Santa Monica Studio"
                                maxlength="150"
                                value="<?= htmlspecialchars($juego['desarrollador']) ?>"
                                required
                            >

                        </div>

                        <!-- GENEROS MULTIPLES -->

                        <div class="col-12">

                            <label class="form-label">
                                Género(s) *
                            </label>

                            <div class="opciones-grupo">

                                <?php foreach ($listaGeneros as $genero): ?>

                                    <label class="opcion-circular">

                                        <input
                                            type="checkbox"
                                            name="genero[]"
                                            value="<?= htmlspecialchars($genero) ?>"
                                            <?= in_array(
                                                $genero,
                                                $juego['genero'],
                                                true
                                            ) ? 'checked' : '' ?>
                                        >

                                        <?= htmlspecialchars($genero) ?>

                                    </label>

                                <?php endforeach; ?>

                            </div>

                        </div>

                        <!-- PLATAFORMAS MULTIPLES -->

                        <div class="col-12">

                            <label class="form-label">
                                Plataforma(s) *
                            </label>

                            <div class="opciones-grupo">

                                <?php foreach ($plataformas as $plataforma): ?>

                                    <label class="opcion-circular">

                                        <input
                                            type="checkbox"
                                            name="plataforma[]"
                                            value="<?= htmlspecialchars($plataforma) ?>"
                                            <?= in_array(
                                                $plataforma,
                                                $juego['plataforma'],
                                                true
                                            ) ? 'checked' : '' ?>
                                        >

                                        <?= htmlspecialchars($plataforma) ?>

                                    </label>

                                <?php endforeach; ?>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Año de lanzamiento *
                            </label>

                            <input
                                type="number"
                                name="anio"
                                class="form-control"
                                min="1950"
                                max="<?= date('Y') ?>"
                                placeholder="Ej. 2018"
                                value="<?= htmlspecialchars((string)$juego['anio']) ?>"
                                required
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Precio *
                            </label>

                            <input
                                type="number"
                                name="precio"
                                class="form-control"
                                min="0"
                                step="0.01"
                                placeholder="Ej. 999.99"
                                value="<?= htmlspecialchars((string)$juego['precio']) ?>"
                                required
                            >

                        </div>

                        <div class="col-12">

                            <label class="form-label">
                                URL de imagen (opcional)
                            </label>

                            <input
                                type="url"
                                name="imagen"
                                class="form-control"
                                placeholder="https://ejemplo.com/imagen.jpg"
                                value="<?= htmlspecialchars($juego['imagen']) ?>"
                            >

                        </div>

                        <div class="col-12">

                            <label class="form-label">
                                Descripción
                            </label>

                            <textarea
                                name="descripcion"
                                class="form-control"
                                rows="4"
                                maxlength="2000"
                                placeholder="Describe brevemente el videojuego..."
                            ><?= htmlspecialchars($juego['descripcion']) ?></textarea>

                        </div>

                        <div class="col-12 mt-4">

                            <button type="submit" class="btn btn-purple btn-lg">

                                <?= $editando
                                    ? 'Guardar cambios'
                                    : 'Registrar videojuego' ?>

                            </button>

                            <a
                                href="catalogo.php"
                                class="btn btn-outline-light btn-lg ms-2"
                            >
                                Cancelar
                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</section>

<?php require_once 'includes/footer.php'; ?>