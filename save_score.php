<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conecta4";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
  die("Conexión fallida: " . mysqli_connect_error());
}
$playerName = $_POST['playerName'];
$score = $_POST['score'];
// Verifica si el jugador ya existe en la tabla
$sql = "SELECT * FROM puntajes WHERE nombre = '$playerName'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
  // Si el jugador existe, actualiza su puntaje
  $sql = "UPDATE puntajes SET puntaje = '$score' WHERE nombre = '$playerName'";
} else {
  // Si el jugador no existe, inserta un nuevo registro
  $sql = "INSERT INTO puntajes (nombre, puntaje) VALUES ('$playerName', '$score')";
}
if (mysqli_query($conn, $sql)) {
  echo "Puntaje guardado correctamente.";
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
mysqli_close($conn);
?>

