<html>
	<head>

	</head>
	<body>
		<h1>Jokes Page</h1>

		<?php
		$servername = "localhost"; // Database server name (often "localhost")
		$username = "jokesdb"; // MySQL username
		$password = "1234"; // MySQL password
		$dbname = "test"; // Name of the database to connect to

		// Crear la conexión
		$conn = mysqli_connect($servername, $username, $password, $dbname);

		// ERROR conexion no se pudo realizar
		if (!$conn) {
	    		die("Connection failed: " . mysqli_connect_error());
		}
		echo "Conectado exitosamente\n";

		// Acciones sobre la base de datos aqui...
		
		
		// Cerrar conexion
		
		mysqli_close($conn);
		echo "Conexion cerrada\n";
		?>
		
	</body>
</html>

