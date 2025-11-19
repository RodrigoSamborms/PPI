<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Procesando Alta</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <h1>Alta de Empleado</h1>
    
    <?php
    include "db_connect.php";
    
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $pass = $_POST['pass'];
    $rol = $_POST['rol'];
    $nombre_imagen = "";
    
    // Procesar imagen si se subió
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $archivo_temporal = $_FILES['imagen']['tmp_name'];
        $extension = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $hash = md5_file($archivo_temporal);
        $nuevo_nombre = $hash . "." . $extension;
        $carpeta = "/home/rodrigo/html/archivos/";
        
        if (copy($archivo_temporal, $carpeta . $nuevo_nombre)) {
            $nombre_imagen = $nuevo_nombre;
        }
    }
    
    // Insertar en la base de datos
    $sql = "INSERT INTO empleados (nombre, apellidos, correo, pass, rol, imagen, eliminado) 
            VALUES ('$nombre', '$apellidos', '$correo', '$pass', $rol, '$nombre_imagen', 0)";
    
    if ($conn->query($sql)) {
        echo "<div class='mensaje exito'>";
        echo "<p>✓ Empleado registrado exitosamente</p>";
        echo "<p><strong>Nombre:</strong> $nombre $apellidos</p>";
        echo "<p><strong>Correo:</strong> $correo</p>";
        if ($nombre_imagen != "") {
            echo "<p><strong>Imagen:</strong> $nombre_imagen</p>";
        }
        echo "</div>";
        echo "<p style='text-align:center;'><a href='Empleados_Lista.php' class='button'>Ver Lista de Empleados</a></p>";
        echo "<p style='text-align:center;'><a href='Empleados_Alta_Forma.php' class='button secondary'>Agregar Otro Empleado</a></p>";
    } else {
        echo "<div class='mensaje error'>";
        echo "<p>✗ Error al registrar empleado: " . $conn->error . "</p>";
        echo "</div>";
        echo "<p style='text-align:center;'><a href='Empleados_Alta_Forma.php' class='button'>Regresar al Formulario</a></p>";
    }
    
    $conn->close();
    ?>
</body>
</html>
