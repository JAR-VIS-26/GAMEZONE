<?php

// Ruta de la carpeta donde guardaremos la base de datos
$dbDir = __DIR__ . '/../database';

// Crear la carpeta si no existe
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0775, true);
}

// Ruta del archivo SQLite
$dbFile = $dbDir . '/gamezone.db';

try {

    // Conexión con SQLite
    $pdo = new PDO('sqlite:' . $dbFile);

    // Configuración de errores
    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

    // Configuración de resultados
    $pdo->setAttribute(
        PDO::ATTR_DEFAULT_FETCH_MODE,
        PDO::FETCH_ASSOC
    );

    // Crear tabla de videojuegos
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS videojuegos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT NOT NULL,
            genero TEXT NOT NULL,
            plataforma TEXT NOT NULL,
            desarrollador TEXT NOT NULL,
            anio INTEGER NOT NULL,
            precio REAL NOT NULL,
            imagen TEXT,
            descripcion TEXT

        )
    ");

} catch (PDOException $e) {

    die(
        'Error de conexión con SQLite: ' .
        htmlspecialchars($e->getMessage())
    );

}
