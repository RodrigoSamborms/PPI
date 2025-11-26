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

// Verificar que se recibieron código e id
if (isset($_POST['codigo']) && isset($_POST['id'])) {
    $codigo = $_POST['codigo'];
    $id = $_POST['id'];
    
    // Consultar si el código ya existe en otro producto (excluyendo el actual)
    $sql = "SELECT id FROM productos WHERE codigo = '$codigo' AND id != $id AND eliminado = 0";
    $result = $conn->query($sql);
    
    if ($result) {
        if ($result->num_rows > 0) {
            echo "0"; // Código ya existe en otro producto
        } else {
            echo "1"; // Código disponible
        }
    } else {
        echo "0"; // Error en la consulta
    }
} else {
    echo "0"; // No se recibieron los datos necesarios
}

$conn->close();
