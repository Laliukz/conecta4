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


