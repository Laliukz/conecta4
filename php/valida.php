<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "topjugadores";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los valores de los nombres y las puntuaciones
$nombre1 = $_POST['nombre1'];
$nombre2 = $_POST['nombre2'];
$puntaje1 = $_POST['puntaje1'];
$puntaje2 = $_POST['puntaje2'];

// Insertar los valores en la base de datos
$sql = "INSERT INTO puntajes (nombre, puntaje) VALUES ('$nombre1', '$puntaje1'), ('$nombre2', '$puntaje2')";
if ($conn->query($sql) === TRUE) {
    echo "Score saved successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
