<?php
include "db_connect.php";

// Verificar que la conexión funciona
if ($conn) {
    echo "1"; // Éxito - conexión establecida
} else {
    echo "0"; // Error - no hay conexión
}

$conn->close();
