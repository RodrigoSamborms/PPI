<?php
include "db_connect.php";

// Verificar que se recibió el correo y el ID del empleado
if (isset($_POST['correo']) && !empty($_POST['correo']) && isset($_POST['id_empleado'])) {
    $correo = $_POST['correo'];
    $id_empleado = intval($_POST['id_empleado']);
    
    // Consultar si el correo ya existe en otro empleado (excluyendo el empleado actual)
    $sql = "SELECT COUNT(*) as total FROM empleados 
            WHERE correo = '$correo' AND id != $id_empleado AND eliminado = 0";
    $result = $conn->query($sql);
    
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row['total'] > 0) {
            echo "1"; // Correo existe en otro empleado
        } else {
            echo "0"; // Correo no existe o es el mismo empleado
        }
    } else {
        echo "0"; // Error en consulta, permitir continuar
    }
} else {
    echo "0"; // No se recibieron datos, permitir continuar
}

$conn->close();
