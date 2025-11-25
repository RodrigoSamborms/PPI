<?php
	$servername = "localhost"; // Database server name (often "localhost")
	$username = "ProduccionDBAdmin"; // MySQL username
	$password = "1234"; // MySQL password
	$dbname = "ProduccionDB"; // Name of the database to connect to

	// Crear la conexión
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	// ERROR conexion no se pudo realizar
	//if (!$conn) {
	//		die("Connection failed: " . mysqli_connect_error());
	//}
	//echo $conn->host_info . "<br>";
	//echo "Conectado exitosamente\n" . "<br>";
