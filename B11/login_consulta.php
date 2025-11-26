<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "db_connect.php";

// Verificar que se recibieron los datos
if (isset($_POST['correo']) && isset($_POST['pass'])) {
    $correo = $_POST['correo'];
    $pass = $_POST['pass']; // Ya viene encriptada con MD5 desde el cliente
    
    // Consultar si el empleado existe, está activo y la contraseña coincide
    $sql = "SELECT id, nombre, apellidos FROM empleados 
            WHERE correo = '$correo' AND pass = '$pass' AND eliminado = 0";
    
    $result = $conn->query($sql);
    
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row && isset($row['id'])) {
            // Login exitoso: crear variables de sesión
            $_SESSION['usuario_id'] = $row['id'];
            $_SESSION['usuario_correo'] = $correo;
            $_SESSION['usuario_nombre'] = $row['nombre'] . " " . $row['apellidos'];
            $_SESSION['sesion_activa'] = true;
            
            echo "1"; // Login exitoso
        } else {
            echo "0"; // Login fallido: usuario no existe, inactivo o contraseña incorrecta
        }
    } else {
        echo "0"; // Error en la consulta
    }
} else {
    echo "0"; // No se recibieron los datos necesarios
}

$conn->close();
