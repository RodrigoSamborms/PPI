<?php
// MostrarDatos.php
// Recibe datos POST desde Formulario12.html y los muestra en pantalla.

function h($s) {
	return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

// Obtener valores con defaults
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$correo = isset($_POST['correo']) ? trim($_POST['correo']) : '';
$sexo = isset($_POST['sexo']) ? $_POST['sexo'] : '';
$boletin = isset($_POST['boletin']) ? $_POST['boletin'] : null; // checkbox: puede no venir
$comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';
$carrera = isset($_POST['carrera']) ? $_POST['carrera'] : '';
$pasw = isset($_POST['pasw']) ? $_POST['pasw'] : '';
$promedio = isset($_POST['promedio']) ? $_POST['promedio'] : '';
$fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';

// Mapear valores de carrera para mostrar texto legible
$carreras = [
	'0' => 'No seleccionada',
	'1' => 'Ing. Informática',
	'2' => 'Ing. Computación',
];

?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<title>Datos Recibidos</title>
</head>
<body>
	<h1>Datos recibidos del formulario</h1>
	<table>
		<tr><th>Nombre</th><td><?= h($nombre) ?></td></tr>
		<tr><th>Correo</th><td><?= h($correo) ?></td></tr>
		<tr><th>Género</th><td><?= h($sexo ?: 'No indicado') ?></td></tr>
		<tr><th>Boletín</th><td><?= $boletin ? 'Sí' : 'No' ?></td></tr>
		<tr><th>Comentario</th><td><?= nl2br(h($comentario)) ?></td></tr>
		<tr><th>Carrera</th><td><?= h(isset($carreras[$carrera]) ? $carreras[$carrera] : $carrera) ?></td></tr>
		<tr><th>Contraseña (hash)</th><td><?= $pasw !== '' ? h(password_hash($pasw, PASSWORD_DEFAULT)) : 'No proporcionada' ?></td></tr>
		<tr><th>Promedio</th><td><?= h($promedio) ?></td></tr>
		<tr><th>Fecha de nacimiento</th><td><?= h($fecha) ?></td></tr>
	</table>

	<p><a href="Formulario12.html">Volver al formulario</a></p>
</body>
</html>

<?php
// fin
?>
