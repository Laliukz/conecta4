<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conecta4";

// Crear la conexión
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verificar la conexión
if (!$conn) {
  die("Conexión fallida: " . mysqli_connect_error());
}

// Consulta para obtener los cinco mejores puntajes
$sql = "SELECT nombre, puntaje FROM puntajes ORDER BY puntaje DESC LIMIT 5";
$result = mysqli_query($conn, $sql);

// Crear un array para almacenar los resultados
$top5 = [];

// Obtener los resultados en un array asociativo
if (mysqli_num_rows($result) > 0) {
  while($row = mysqli_fetch_assoc($result)) {
    $top5[] = $row;
  }
} else {
  echo "0 resultados";
}

// Convertir el array en un objeto JSON
echo json_encode($top5);

// Cerrar la conexión
mysqli_close($conn);
?>
