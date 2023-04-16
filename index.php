<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>conecta 4</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conecta4";
// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);
// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
// Si se ha enviado el formulario de puntuación, validar e insertar la puntuación en la base de datos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nombre']) && isset($_POST['puntaje'])) {
    // Validar los datos del formulario
    $nombre = filter_var($_POST['nombre'], FILTER_SANITIZE_STRING);
    $puntaje = filter_var($_POST['puntaje'], FILTER_VALIDATE_INT);
    if ($nombre && $puntaje) {
       // Insertar puntuación en la base de datos
      $sql = "INSERT INTO jugadores (nombre, puntaje) VALUES ('$nombre', '$puntaje')";
      if ($conn->query($sql) === TRUE) {
          echo "Puntuación guardada con éxito";
      } else {
          echo "Error al guardar la puntuación: " . $sql . "<br>" . $conn->error;
      }
    } else {
        echo "Datos del formulario no válidos";
    }
}
// Mostrar la tabla de clasificación en formato HTML
$sql = "SELECT nombre, puntaje FROM jugadores ORDER BY puntaje DESC LIMIT 5";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
  $tabla_clasificacion = "<h1>Top 5 Jugadores</h1>";
  $tabla_clasificacion .= "<table id='jugadores'>";
  $tabla_clasificacion .= "<thead><tr><th>Nombre</th><th>Puntuación</th></tr></thead>";
  $tabla_clasificacion .= "<tbody>";
  while ($row = $result->fetch_assoc()) {
      $tabla_clasificacion .= "<tr><td>" . htmlspecialchars($row['nombre']) . "</td><td>" . htmlspecialchars($row['puntaje']) . "</td></tr>";
  }
  $tabla_clasificacion .= "</tbody></table>";
} else {
  $tabla_clasificacion = "<p>No hay resultados</p>";
}

// Cerrar conexión a la base de datos
$conn->close();
?>
    <h1>Conecta 4</h1>
    <div id="gameset">
        <label for="size">Tamaño del tablero:</label>
            <input type="number" id="size" min="6" max="10" value="6" required>
        <label for="name1">Nombre del jugador 1:</label>
            <input type="text" id="player1" required>
        <label for="name2">Nombre del jugador 2:</label>
            <input type="text" id="player2" required>
        <button id="iniciarj" onclick="startGame()">Comenzar juego</button>
    </div>
    <br><br>
    <canvas id="canvas"></canvas>
    <br> <br>
    <div id="actj">
        <span id="jug">Jugador actual: </span>
        <span id="player"></span>
    </div>
    <br>
        <table id="finjuego" style="display: none;">
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
          <br>
              <button id="btnTabla">Puntuaciones</button>
    <br><br>
    <div id="botonesf">
        <button id="restartButton" onclick="startGame()">Jugar de nuevo</button>
        <button id="reiniciar" onclick="window.location.href='index.php'">Reiniciar juego</button>
    </div>
    <h1>Top 5 Jugadores</h1>
<table id="jugadores">
	<thead>
		<tr>
      <?= $tabla_clasificacion ?>
		</tr>
	</thead>

</table>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
let size = 0;
let player1 = '';
let player2 = '';
let nextPlayer = '';
let player1Wins = 0;
let empate = 0;
let player2Wins = 0;
let gameOver = false;
let board = [];
function dibujaTablero() {
  const cellSize = canvas.width / size;
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.beginPath();
  for (let i = 1; i < size; i++) {
    ctx.moveTo(i * cellSize, 0);
    ctx.lineTo(i * cellSize, canvas.height);
    ctx.moveTo(0, i * cellSize);
    ctx.lineTo(canvas.width, i * cellSize);
  }
  ctx.stroke();
}
function drawCircle(x, y) {
  const cellSize = canvas.width / size;
  const centerX = (x + 0.5) * cellSize;
  const centerY = (y + 0.5) * cellSize;
  const radius = cellSize / 2 - 10;
  if (player === 'X') {
    ctx.fillStyle = 'red';
    ctx.strokeStyle = 'red';
  } else {
    ctx.fillStyle = 'blue';
    ctx.strokeStyle = 'blue';
  }
  ctx.beginPath();
  ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
  ctx.fill();
  ctx.beginPath();
  ctx.arc(centerX, centerY, radius, 0, 2 * Math.PI);
  ctx.stroke();
}
function startGame() {
  size = parseInt(document.getElementById('size').value);
  player1 = document.getElementById('player1').value;
  player2 = document.getElementById('player2').value;
  player = 'X';
  board = [];
  gameOver = false;
  // Inicializar la matriz del tablero
  for (let i = 0; i < size; i++) {
    let row = [];
    for (let j = 0; j < size; j++) {
        row.push('');
    }
    board.push(row);
  }
  // Establecer el tamaño del canvas
  canvas.width = 500;
  canvas.height = 500;
  dibujaTablero();
  // Mostrar el jugador actual
  document.getElementById('player').textContent = player;
  canvas.addEventListener('click', handleClick);
  //document.getElementById('iniciarj').disabled = true;
}
function handleClick(event) {
  // Verificar si ambos nombres han sido introducidos antes de comenzar el juego
  const player1 = document.getElementById('player1').value;
  const player2 = document.getElementById('player2').value;
  if (player1 === '' || player2 === '') {
      alert('Por favor introduce ambos nombres para comenzar el juego.');
      document.getElementById('iniciarj').disabled = false;
      return;
  } else{
      document.getElementById('iniciarj').disabled = true;
  }
  if (gameOver){
      alert('El juego termino. Pulsa el boton de jugar de nuevo para iniciar otra partida');
      return;
  }
  const cellSize = canvas.width / size;
  const x = Math.floor(event.offsetX / cellSize);
  //const y = Math.floor(event.offsetY / cellSize);
  let y = size - 1;
  while (y >= 0 && board[y][x] !== '') {
    y--;
  }
  if (y >= 0) {
    board[y][x] = player;
    if (player === 'X') {
      drawCircle(x, y);
    } else {
      drawCircle(x, y);
    }
    checkForWin();
    switchPlayers();
    if (player === 'O' && !gameOver) {
        player = 'O';
    }
  } else {
    alert('Esta casilla ya está ocupada. Por favor elige otra.');
  }
}
function switchPlayers() {
  if (gameOver) {
    return;
  }
  player = player === 'X' ? 'O' : 'X';
  document.getElementById('player').textContent = player === 'X' ? player1 : player2;
  if (player === 'X') {
    alert(turnoDiv.textContent = "es turno de " + player1);
  } else {
    turnoDiv.textContent = "es turno de " + player2;
  }
}
function checkForWin() {
  // Verificar filas
  for (let y = 0; y < size; y++) {
    for (let x = 0; x < size - 3; x++) {
      if (board[y][x] !== '' &&
        board[y][x] === board[y][x+1] &&
        board[y][x] === board[y][x+2] &&
        board[y][x] === board[y][x+3]) {
        announceWinner(board[y][x], 'horizontal');
        gameOver = true;
        return;
      }
    }
  }
  // Verificar columnas
  for (let x = 0; x < size; x++) {
    for (let y = 0; y < size - 3; y++) {
      if (board[y][x] !== '' &&
        board[y][x] === board[y+1][x] &&
        board[y][x] === board[y+2][x] &&
        board[y][x] === board[y+3][x]) {
        announceWinner(board[y][x], 'vertical');
        gameOver = true;
        return;
      }
    }
  }
    // Verificar diagonal principal
  for (let x = 0; x < size - 3; x++) {
    for (let y = 0; y < size - 3; y++) {
      if (board[y][x] !== '' &&
        board[y][x] === board[y+1][x+1] &&
        board[y][x] === board[y+2][x+2] &&
        board[y][x] === board[y+3][x+3]) {
        announceWinner(board[y][x], 'diagonal');
        gameOver = true;
        return;
      }
    }
  }
  // Verificar diagonal secundaria
  for (let x = size - 1; x >= 3; x--) {
    for (let y = 0; y < size - 3; y++) {
      if (board[y][x] !== '' &&
        board[y][x] === board[y+1][x-1] &&
        board[y][x] === board[y+2][x-2] &&
        board[y][x] === board[y+3][x-3]) {
        announceWinner(board[y][x], 'diagonal');
        gameOver = true;
        return;
      }
    }
  }
  // Comprobar si el tablero está lleno y no hay ganador
  let tableroLleno = true;
  for (let i = 0; i < size; i++) {
    for (let j = 0; j < size; j++) {
      if (board[i][j] === '') {
        tableroLleno = false;
        break;
      }
    }
    if (!tableroLleno) {
      break;
    }
  }
  if (tableroLleno) {
    alert(`${player1} y ${player2} han empatado!`)
    empate++;
    document.getElementById('empates').textContent = `Empates: ${empate}`;
    gameOver = true;
    document.getElementById('player').textContent = '';
  }
}
  // Función para anunciar al ganador y terminar el juego
function announceWinner(winner, direction) {
  const player1p = document.getElementById('jugador1p');
  const player2p = document.getElementById('jugador2p');
  //const j1 = document.getElementById('j1');
  const empates = document.getElementById('empates');
    if (winner === 'X') {
        alert(`${player1} ganó la partida!`);
        j1.textContent = `Puntuación ${player1}:`
        j2.textContent = `Puntuación ${player2}:`
        player1p.textContent = `${++player1Wins}`;
    } else {
        alert(`${player2} ganó la partida!`);
        j1.textContent = `Puntuación ${player1}:`
        j2.textContent = `Puntuación ${player2}:`
        player2p.textContent = `${++player2Wins}`;
    }
      gameOver = true;
}
  //para mostrar u ocultar la tabla de puntos
btnTabla.addEventListener("click", function() {
  const btnTabla = document.getElementById("btnTabla");
  const tabla = document.getElementById("finjuego");
    if (tabla.style.display === 'none') {
        tabla.style.display = 'table';
        btnTabla.textContent = 'Ocultar puntuaciones';
    } else {
        tabla.style.display = 'none';
        btnTabla.textContent = 'Puntuaciones';
    }
});
function guardarPuntuacion() {
  const nombre1 = document.getElementById('player1').value;
  const nombre2 = document.getElementById('player2').value;
  const puntuacion1 = player1Wins;
  const puntuacion2 = player2Wins;
  $.ajax("index.php", { nombre: nombre1, puntaje: puntuacion1 }, function() {
    console.log('Puntuación del jugador 1 guardada con éxito');
  });
  $.ajax("index.php", { nombre: nombre2, puntaje: puntuacion2 }, function() {
    console.log('Puntuación del jugador 2 guardada con éxito');
    location.reload();
  }); 
}</script>
</body>
</html>
