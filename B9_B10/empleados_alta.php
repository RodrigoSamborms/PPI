<?php
// Iniciar sesión para validación
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar sesión
if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Este archivo solo debe procesar peticiones POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: empleados_alta_form.php');
    exit;
}

include "db_connect.php";
include "Subir_foto.php";

// Verificar que la conexión funciona
if ($conn) {
    if (isset($_POST['nombre'], $_POST['apellidos'], $_POST['correo'], $_POST['pass'], $_POST['rol'])) {
        
        // Procesar la imagen usando Subir_foto.php
        $resultado_foto = subirFoto();
        $nombre_imagen = $resultado_foto['nombre'];
        
        // Preparar datos para INSERT
        $nombre = $_POST['nombre'];
        $apellidos = $_POST['apellidos'];
        $correo = $_POST['correo'];
        $pass = $_POST['pass']; // Ya viene encriptado con MD5 desde el formulario
        $rol = $_POST['rol'];
        $imagen = $nombre_imagen; // Puede estar vacío si no se subió imagen
        
        // Insertar en la base de datos
        $sql = "INSERT INTO empleados (nombre, apellidos, correo, pass, rol, imagen, eliminado) 
                VALUES ('$nombre', '$apellidos', '$correo', '$pass', $rol, '$imagen', 0)";
        
        if ($conn->query($sql) === TRUE) {
            // Éxito: redirigir a la lista de empleados
            $conn->close();
            header('Location: empleados_lista.php');
            exit;
        } else {
            // Error en el INSERT
            $error_msg = $conn->error;
            
            // Traducir errores comunes de MySQL al español
            if (strpos($error_msg, "Duplicate entry") !== false) {
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
            echo "<?php include 'menu.php'; ?>";
            echo "<div class='mensaje error'>";
            echo "<h2>Error al registrar empleado</h2>";
            echo "<p>" . htmlspecialchars($error_msg) . "</p>";
            echo "<p><a href='empleados_alta_form.php' class='button'>Volver al Formulario</a></p>";
            echo "</div></body></html>";
            $conn->close();
            exit;
        }
    }
}
