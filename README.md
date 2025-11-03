# Conecta4 - Versión Local

Juego de "4 en línea" implementado con HTML/CSS/JavaScript y PHP (+ MySQL) para guardar puntuaciones.

## Estructura

- `index.php` - página principal.
- `4enlinea.html` - versión estática.
- `css/style.css` - estilos.
- `js/game.js` - lógica del juego.
- `php/` - endpoints PHP:
	- `get_top5.php` - devuelve top5 de puntuaciones en JSON.
	- `save_score.php` - guarda puntuaciones (POST).
	- `puntajes_json.php`, `valida.php` - utilidades.
	- `setup_db.php` - script para crear la base de datos y tabla localmente.

## Requisitos

- WAMP / XAMPP / cualquier pila PHP + MySQL local.
- Navegador moderno con canvas.

## Instalación y puesta en marcha (local)

1. Copia el proyecto al directorio de tu servidor local, por ejemplo `f:\\wamp64\\www\\conecta4-main`.
2. Abre en el navegador: `http://localhost/conecta4-main/index.php`.
3. Si ves un mensaje sobre la base de datos faltante, ejecuta `php/setup_db.php` desde el navegador para crear la base de datos y la tabla `puntajes` (solo en desarrollo):

	 - `http://localhost/conecta4-main/php/setup_db.php`

	 IMPORTANTE: elimina o protege este script en producción.

4. Rellena los nombres de los jugadores, elige el tamaño del tablero y pulsa "Iniciar".

## Uso

- El canvas es responsive.
- Las fichas se guardan en el backend cuando se anota un punto (si el endpoint está disponible).
- La tabla de puntuaciones se muestra a la izquierda en pantallas grandes y se apila en móviles.

## Desarrollo

- `js/game.js` contiene las funciones principales: `startGame()`, `handleClick()`, `checkForWin()`, `switchPlayers()`, `saveScore()` y `updateTop5()`.
- Para cambiar tamaños o comportamiento responsive, edita `css/style.css` y `js/game.js` (función `adjustCanvasSize`).

## Notas de seguridad

- El proyecto incluye un script `php/setup_db.php` para creación rápida de la base de datos en desarrollo. No usar en entornos productivos sin refactorizar autenticación y permisos.

## Cambios recientes
- `js/game.js` ahora muestra el nombre del jugador actual en lugar de "X/O".

