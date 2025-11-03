
const canvas = document.getElementById('canvas');
const ctx = canvas.getContext('2d');
let size = 0;
let player1 = '';
let player2 = '';
let nextPlayer = '';
let player = ''; 
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
  const radius = Math.max(6, cellSize / 2 - 10);
  const mark = (arguments.length > 2 && arguments[2]) ? arguments[2] : player;
  if (mark === 'X') {
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

// Redibuja todo el tablero (usa la matriz 'board')
function renderBoard() {
  for (let y = 0; y < board.length; y++) {
    for (let x = 0; x < board[y].length; x++) {
      if (board[y][x] && board[y][x] !== '') {
        drawCircle(x, y, board[y][x]);
      }
    }
  }
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
  // Establecer el tamaño del canvas en función del ancho de pantalla
  adjustCanvasSize();
  dibujaTablero();
  // Mostrar el jugador actual
  const playerElem = document.getElementById('player');
  if (playerElem) playerElem.textContent = (player === 'X') ? player1 : player2;
  canvas.addEventListener('click', handleClick);
}

// Ajusta el tamaño del canvas según la pantalla y redibuja tablero y fichas
function adjustCanvasSize() {
  let newSize;
  const ww = window.innerWidth;
  if (ww < 600) {
    newSize = Math.floor(ww * 0.9);
  } else if (ww < 1000) {
    newSize = Math.floor(ww * 0.6);
  } else {
    newSize = 500;
  }
  newSize = Math.max(240, Math.min(newSize, 700));
  canvas.width = newSize;
  canvas.height = newSize;
  dibujaTablero();
  renderBoard();
}

// Redimensionar el canvas si la ventana cambia tamaño durante el juego
window.addEventListener('resize', function() {
  if (board && board.length > 0) {
    adjustCanvasSize();
  }
});

function handleClick(event) {
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
  let y = size - 1;
  while (y >= 0 && board[y][x] !== '') {
    y--;
  }
  if (y >= 0) {
    board[y][x] = player;
    drawCircle(x, y);
    checkForWin();
    switchPlayers();
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
  // Intentar actualizar turnoDiv si existe
  try {
    if (player === 'X') {
      if (typeof turnoDiv !== 'undefined') turnoDiv.textContent = "es turno de " + player1;
    } else {
      if (typeof turnoDiv !== 'undefined') turnoDiv.textContent = "es turno de " + player2;
    }
  } catch (e) {}
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
    const empElem = document.getElementById('empates');
    if (empElem) empElem.textContent = `Empates: ${empate}`;
    gameOver = true;
    const playerElem = document.getElementById('player');
    if (playerElem) playerElem.textContent = '';
  }
}

function announceWinner(winner, direction) {
  const player1p = document.getElementById('jugador1p');
  const player2p = document.getElementById('jugador2p');
  const j1 = document.getElementById('j1');
  const j2 = document.getElementById('j2');
  if (winner === 'X') {
    alert(`${player1} ganó la partida!`);
    if (j1) j1.textContent = `Puntuación ${player1}:`;
    if (j2) j2.textContent = `Puntuación ${player2}:`;
    if (player1p) player1p.textContent = `${++player1Wins}`;
    winner = player1;
  } else {
    alert(`${player2} ganó la partida!`);
    if (j1) j1.textContent = `Puntuación ${player1}:`;
    if (j2) j2.textContent = `Puntuación ${player2}:`;
    if (player2p) player2p.textContent = `${++player2Wins}`;
    winner = player2;
  }
  gameOver = true;
  // Guardar puntuación en servidor
  if (typeof saveScore === 'function') {
    let playerName = winner;
    let playerScore = (winner === player1) ? player1Wins : player2Wins;
    saveScore(playerName, playerScore);
  }
}

function saveScore(playerName, score) {
  $.ajax({
    type: 'POST',
    url: 'php/save_score.php',
    data: {
      'playerName': playerName,
      'score': score
    },
    success: function(response) {
      updateTop5();
    }
  });
}

function updateTop5() {
  $.ajax({
    type: 'GET',
    url: 'php/get_top5.php',
    success: function(response) {
      // El endpoint devuelve JSON;
      try {
        const data = JSON.parse(response);
        const table = document.getElementById('puntajes');
        if (table) {
          // reconstruir tabla
          table.innerHTML = '<thead><tr><th>Nombre</th><th>Puntuación</th></tr></thead>';
          const tbody = document.createElement('tbody');
          data.forEach(item => {
            const tr = document.createElement('tr');
            const td1 = document.createElement('td'); td1.textContent = item.nombre;
            const td2 = document.createElement('td'); td2.textContent = item.puntaje;
            tr.appendChild(td1); tr.appendChild(td2);
            tbody.appendChild(tr);
          });
          table.appendChild(tbody);
        }
      } catch (e) {
        const table = document.getElementById('puntajes');
        if (table) table.innerHTML = response;
      }
    }
  });
}
