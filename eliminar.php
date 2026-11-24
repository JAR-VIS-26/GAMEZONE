<?php

// Conexión con SQLite
require_once 'includes/db.php';

// Permitir únicamente solicitudes POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: catalogo.php');
    exit;
}

// Obtener ID del videojuego
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

// Verificar ID
if (!$id || $id < 1) {
    header('Location: catalogo.php');
    exit;
}

// Consultar si existe el videojuego
$consulta = $pdo->prepare(
    "SELECT id FROM videojuegos WHERE id = :id"
);

$consulta->execute([
    ':id' => $id
]);

$juego = $consulta->fetch();

if (!$juego) {
    header('Location: catalogo.php');
    exit;
}

// Eliminar videojuego
$consulta = $pdo->prepare(
    "DELETE FROM videojuegos WHERE id = :id"
);

$consulta->execute([
    ':id' => $id
]);

// Regresar al catálogo
header('Location: catalogo.php');
exit;

?>