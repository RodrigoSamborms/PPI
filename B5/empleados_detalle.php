<?php
// Verificar que se recibió el ID del empleado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: empleados_lista.php');
    exit;
}

$id_empleado = intval($_GET['id']);

include "db_connect.php";

// Consultar los datos del empleado
$sql = "SELECT id, nombre, apellidos, correo, rol, eliminado FROM empleados WHERE id = $id_empleado";
$result = $conn->query($sql);

// Verificar que el empleado existe
if (!$result || $result->num_rows == 0) {
    echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>";
    echo "<title>Error</title><link rel='stylesheet' href='estilos.css'></head><body>";
    echo "<div class='mensaje error'>";
    echo "<h2>Empleado no encontrado</h2>";
    echo "<p>El empleado con ID $id_empleado no existe en la base de datos.</p>";
    echo "<p><a href='empleados_lista.php' class='button'>Volver al Listado</a></p>";
    echo "</div></body></html>";
    $conn->close();
    exit;
}

$empleado = $result->fetch_assoc();
$conn->close();

// Preparar datos para mostrar
$nombre_completo = $empleado['nombre'] . " " . $empleado['apellidos'];
$rol_texto = ($empleado['rol'] == 2) ? 'Gerente' : 'Ejecutivo';
$status = ($empleado['eliminado'] == 0) ? 'Activo' : 'Inactivo';
$status_class = ($empleado['eliminado'] == 0) ? 'activo' : 'inactivo';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Empleado</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Detalle del Empleado</h1>
    
    <div class="detalle-card">
        <div class="campo">
            <span class="label">ID:</span>
            <span class="valor"><?php echo $empleado['id']; ?></span>
        </div>
        
        <div class="campo">
            <span class="label">Nombre Completo:</span>
            <span class="valor"><?php echo htmlspecialchars($nombre_completo); ?></span>
        </div>
        
        <div class="campo">
            <span class="label">Correo Electrónico:</span>
            <span class="valor"><?php echo htmlspecialchars($empleado['correo']); ?></span>
        </div>
        
        <div class="campo">
            <span class="label">Rol:</span>
            <span class="valor"><?php echo $rol_texto; ?></span>
        </div>
        
        <div class="campo">
            <span class="label">Status:</span>
            <span class="status <?php echo $status_class; ?>"><?php echo $status; ?></span>
        </div>
    </div>
    
    <p style="text-align:center; margin-top: 30px;">
        <a href="empleados_lista.php" class="button">Volver al Listado</a>
    </p>
</body>
</html>
