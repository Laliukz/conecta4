<?php
// Script de configuración (solo usar en entorno local).
// Crea la base de datos `conecta4` y la tabla `puntajes` si no existen.

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conecta4";

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($servername, $username, $password);
if (!$conn) {
    echo "Error: no se puede conectar al servidor MySQL: " . mysqli_connect_error();
    exit;
}

// Crear la base de datos si no existe
$sql = "CREATE DATABASE IF NOT EXISTS `" . $dbname . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if (!mysqli_query($conn, $sql)) {
    echo "Error creando la base de datos: " . mysqli_error($conn);
    mysqli_close($conn);
    exit;
}

// Seleccionar la base de datos
mysqli_select_db($conn, $dbname);

// Crear tabla `puntajes` si no existe
$createTable = "CREATE TABLE IF NOT EXISTS `puntajes` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL,
  `puntaje` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!mysqli_query($conn, $createTable)) {
    echo "Error creando la tabla puntajes: " . mysqli_error($conn);
    mysqli_close($conn);
    exit;
}

echo "Base de datos '<strong>conecta4</strong>' y tabla '<strong>puntajes</strong>' están listas.";
echo "<br><a href=\"../index.php\">Volver a la página principal</a>";
echo "<p><em>Nota: elimina o protege este script después de usarlo.</em></p>";

mysqli_close($conn);
?>
