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

// Verificar que se recibió el código
if (isset($_POST['codigo'])) {
    $codigo = $_POST['codigo'];
    
    // Consultar si el código ya existe (sin importar si está eliminado o no)
    $sql = "SELECT id FROM productos WHERE codigo = '$codigo'";
    $result = $conn->query($sql);
    
    if ($result) {
        if ($result->num_rows > 0) {
            echo "0"; // Código ya existe
        } else {
            echo "1"; // Código disponible
        }
    } else {
        echo "0"; // Error en la consulta
    }
} else {
    echo "0"; // No se recibió el código
}

$conn->close();
