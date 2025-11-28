<?php
// Iniciar sesión para validación
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar sesión
if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
    echo "0";
    exit;
}

include "db_connect.php";

// Verificar que se recibió el correo
if (isset($_POST['correo']) && !empty($_POST['correo'])) {
    $correo = $_POST['correo'];
    
    // Consultar si el correo ya existe (sin importar si está eliminado o no)
    $sql = "SELECT COUNT(*) as total FROM empleados WHERE correo = '$correo'";
    $result = $conn->query($sql);
    
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row['total'] > 0) {
            echo "1"; // Correo existe
        } else {
            echo "0"; // Correo no existe
        }
    } else {
        echo "0"; // Error en consulta
    }
} else {
    echo "0"; // No se recibieron datos
}

$conn->close();
