<?php
// Este archivo solo debe procesar peticiones POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: empleados_lista.php');
    exit;
}

include "db_connect.php";
include "Subir_foto.php";

// Verificar que la conexión funciona y que se recibieron todos los datos
if ($conn && isset($_POST['id'], $_POST['nombre'], $_POST['apellidos'], $_POST['correo'], $_POST['rol'])) {
    
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $pass = isset($_POST['pass']) ? $_POST['pass'] : '';
    
    // Verificar que el empleado existe y está activo
    $sql_check = "SELECT id, imagen FROM empleados WHERE id = $id AND eliminado = 0";
    $result_check = $conn->query($sql_check);
    
    if (!$result_check || $result_check->num_rows == 0) {
        echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>";
        echo "<title>Error</title><link rel='stylesheet' href='estilos.css'></head><body>";
        echo "<div class='mensaje error'>";
        echo "<h2>Error al actualizar</h2>";
        echo "<p>El empleado no existe o está inactivo.</p>";
        echo "<p><a href='empleados_lista.php' class='button'>Volver al Listado</a></p>";
        echo "</div></body></html>";
        $conn->close();
        exit;
    }
    
    $empleado_actual = $result_check->fetch_assoc();
    $imagen_actual = $empleado_actual['imagen'];
    
    // Procesar la imagen si se subió una nueva
    $resultado_foto = subirFoto();
    $nombre_imagen = $resultado_foto['nombre'];
    
    // Si no se subió imagen nueva, mantener la actual
    if (empty($nombre_imagen)) {
        $nombre_imagen = $imagen_actual;
    }
    
    // Construir la consulta UPDATE
    // Incluir contraseña solo si se proporcionó
    // Incluir imagen (siempre, ya sea la nueva o la actual)
    if (!empty($pass) && trim($pass) !== '') {
        $sql = "UPDATE empleados 
                SET nombre = '$nombre', apellidos = '$apellidos', correo = '$correo', 
                    rol = $rol, pass = '$pass', imagen = '$nombre_imagen' 
                WHERE id = $id AND eliminado = 0";
    } else {
        $sql = "UPDATE empleados 
                SET nombre = '$nombre', apellidos = '$apellidos', correo = '$correo', 
                    rol = $rol, imagen = '$nombre_imagen' 
                WHERE id = $id AND eliminado = 0";
    }
    
    if ($conn->query($sql) === TRUE) {
        // Éxito: redirigir a la lista de empleados
        $conn->close();
        header('Location: empleados_lista.php');
        exit;
    } else {
        // Error en el UPDATE
        $error_msg = $conn->error;
        
        // Traducir errores comunes de MySQL al español
        if (strpos($error_msg, "Duplicate entry") !== false) {
            // Extraer el valor duplicado y el campo
            preg_match("/Duplicate entry '([^']+)' for key '([^']+)'/", $error_msg, $matches);
            if (count($matches) >= 3) {
                $valor = $matches[1];
                $campo = $matches[2];
                $error_msg = "Entrada duplicada '$valor' para el campo '$campo'";
            } else {
                $error_msg = "El correo electrónico ya está registrado en el sistema";
            }
        }
        
        echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>";
        echo "<title>Error</title><link rel='stylesheet' href='estilos.css'></head><body>";
        echo "<div class='mensaje error'>";
        echo "<h2>Error al actualizar empleado</h2>";
        echo "<p>" . htmlspecialchars($error_msg) . "</p>";
        echo "<p><a href='empleados_editar_form.php?id=$id' class='button'>Volver al Formulario</a></p>";
        echo "</div></body></html>";
        $conn->close();
        exit;
    }
    
} else {
    echo "<!DOCTYPE html><html lang='es'><head><meta charset='UTF-8'>";
    echo "<title>Error</title><link rel='stylesheet' href='estilos.css'></head><body>";
    echo "<div class='mensaje error'>";
    echo "<p>No se recibieron todos los datos del formulario</p>";
    echo "<p><a href='empleados_lista.php' class='button'>Volver al Listado</a></p>";
    echo "</div></body></html>";
    if ($conn) $conn->close();
    exit;
}
