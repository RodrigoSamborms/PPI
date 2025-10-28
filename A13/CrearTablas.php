<?php
// CrearTablas.php
// Formulario básico (sin CSS) que permite seleccionar un número entre 0 y 5000
// Si se envía un valor mayor a 0, se genera una tabla dinámica de N filas por 1 columna.

$rows = null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Obtener y validar como entero
	$rows = filter_input(INPUT_POST, 'rows', FILTER_VALIDATE_INT);

	// Si no es un entero válido o es <= 0, no hacer nada (mensaje informativo)
	if ($rows === false || $rows === null || $rows <= 0) {
		$message = 'No se realizó ninguna acción. Seleccione un valor mayor a 0.';
		$rows = 0;
	} else {
		// Asegurar un máximo razonable por seguridad
		if ($rows > 5000) {
			$rows = 5000;
		}
	}
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Crear tabla Nx1</title>
</head>
<body>
	<h1>Crear tabla Nx1</h1>

	<form method="post" action="">
		<label for="rows">Número de filas (0-5000):</label>
		<select name="rows" id="rows">
			<?php
			// Valor seleccionado por defecto: 0 (si no hay POST)
			$selected = isset($rows) ? (int)$rows : 0;

			// Generar opciones del 0 al 5000
			for ($i = 0; $i <= 5000; $i++) {
				$sel = ($i === $selected) ? ' selected' : '';
				// Escape no necesario para enteros, pero mantenemos formato seguro
				echo "<option value=\"$i\"$sel>$i</option>\n";
			}
			?>
		</select>
		<button type="submit">Crear tabla</button>
	</form>

	<?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
		<?php if ($rows > 0): ?>
			<h2>Tabla generada (<?php echo $rows; ?> filas)</h2>
			<table border="1" cellpadding="4" cellspacing="0">
				<?php for ($r = 1; $r <= $rows; $r++): ?>
					<tr><td><?php echo $r; ?></td></tr>
				<?php endfor; ?>
			</table>
		<?php else: ?>
			<p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
		<?php endif; ?>
	<?php endif; ?>

</body>
</html>

