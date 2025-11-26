<?php
// Conexión a la base de datos
$conn = mysqli_connect("localhost", "ProduccionDBAdmin", "1234", "ProduccionDB");

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Configurar charset
mysqli_set_charset($conn, "utf8");
