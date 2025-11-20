<?php
// Este archivo solo debe procesar peticiones POST
// Si se accede directamente, redirigir al formulario
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
            echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Error</title>";
            echo "<link rel='stylesheet' href='estilos.css'></head><body>";
            echo "<div class='mensaje error'>";
            echo "<h2>Error al registrar empleado</h2>";
            echo "<p>" . $conn->error . "</p>";
            echo "<p><a href='empleados_alta_form.php' class='button'>Volver al Formulario</a></p>";
            echo "</div></body></html>";
            $conn->close();
            exit;
        }
        
    } else {
        echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Error</title>";
        echo "<link rel='stylesheet' href='estilos.css'></head><body>";
        echo "<div class='mensaje error'>";
        echo "<p>No se recibieron todos los datos del formulario</p>";
        echo "<p><a href='empleados_alta_form.php' class='button'>Volver al Formulario</a></p>";
        echo "</div></body></html>";
        $conn->close();
        exit;
    }
} else {
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Error</title>";
    echo "<link rel='stylesheet' href='estilos.css'></head><body>";
    echo "<div class='mensaje error'>";
    echo "<p>Error de conexión a la base de datos</p>";
    echo "<p><a href='empleados_alta_form.php' class='button'>Volver al Formulario</a></p>";
    echo "</div></body></html>";
    exit;
}
