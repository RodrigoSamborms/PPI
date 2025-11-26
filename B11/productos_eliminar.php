<?php
// Iniciar sesión
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Validar sesión activa
if (!isset($_SESSION['usuario_id']) || empty($_SESSION['usuario_id'])) {
    echo "0";
    exit;
}

include "db_connect.php";

// Verificar que se recibió el ID
if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Soft delete: marcar como eliminado
    $sql = "UPDATE productos SET eliminado = 1 WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "1"; // Éxito
    } else {
        echo "0"; // Error
    }
} else {
    echo "0"; // No se recibió el ID
}

$conn->close();
