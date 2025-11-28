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

// Verificar que se recibió el ID
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];
    
    // Actualizar el campo eliminado a 1
    $sql = "UPDATE empleados SET eliminado = 1 WHERE id = $id";
    
    if ($conn->query($sql)) {
        // Éxito
        echo "1";
    } else {
        // Error en la consulta
        echo "0";
    }
} else {
    // No se recibió ID válido
    echo "0";
}

$conn->close();
