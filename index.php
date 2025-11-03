<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>conecta 4</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
// Datos de conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conecta4";

// Intentamos conectar al servidor MySQL y comprobar si la base de datos existe.
// Evitamos que mysqli lance una excepción fatal si la BD no existe.
mysqli_report(MYSQLI_REPORT_OFF);
$dbError = null;
$conn = @mysqli_connect($servername, $username, $password);
if (!$conn) {

    $dbError = "Conexión al servidor MySQL fallida: " . mysqli_connect_error();
} else {

    if (!@mysqli_select_db($conn, $dbname)) {
        $dbError = "Base de datos '<strong>conecta4</strong>' no encontrada. " .
                   "Puedes crearla automáticamente ejecutando <a href=\"php/setup_db.php\">php/setup_db.php</a> (sólo para entorno local).";
    }
}
if ($conn) mysqli_close($conn);
?>
<?php if ($dbError): ?>
    <div style="background:#fee;border:1px solid #f99;padding:10px;margin:10px 0;">
        <strong>Atención:</strong>
        <span><?php echo $dbError; ?></span>
    </div>
<?php endif; ?>
    <h1>Conecta 4</h1>
    <div id="main">
    <div id="gameset">
        <label for="size">Tamaño del tablero:</label>
            <input type="number" id="size" min="6" max="10" value="6" required>
        <label for="name1">Nombre del jugador 1:</label>
            <input type="text" id="player1" required>
        <label for="name2">Nombre del jugador 2:</label>
            <input type="text" id="player2" required>
        <button id="iniciarj" onclick="startGame()">Comenzar juego</button>
        <div id="botonesf">
            <button id="restartButton" onclick="startGame()">Jugar de nuevo</button>
            <button id="reiniciar" onclick="window.location.href='index.php'">Reiniciar juego</button>
        </div>
    </div>
    
    <canvas id="canvas"></canvas>
        <div id="scorepanel">
            <table id="finjuego" style="width:100%;">
                        <tr>
                            <td id="j1">Puntuacion jugador 1:</td>
                            <td id="jugador1p">0</td>
                        </tr>
                        <tr>
                            <td id="j2">Puntuacion jugador 2:</td>
                            <td id="jugador2p">0</td>
                        </tr>
                        <tr>
                            <td>Empates:</td>
                            <td id="empates">0</td>
                        </tr>
                </table>
        </div>
        <div></div>
            <div id="actj">
                <span id="jug">Jugador actual: </span>
                <span id="player"></span>
            </div>
        </div> 
    <br> <br>

    <br>
        <br><br>

    <h1>Top 5 Jugadores</h1>
<table id="puntajes">
	<thead>
		<tr>
			<th>Nombre</th>
			<th>Puntuación</th>
		</tr>
	</thead>
</table>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/game.js"></script>
</body>
</html>