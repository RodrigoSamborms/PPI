<html>
	<head>
		<link rel="stylesheet" href="estilos.css">
		
	</head>
	<body>
		<h1>Jokes Page</h1>

		<?php
		include "db_connect.php";
		
		//include "search_all_jokes.php";
		?>
		
		<form action="search_keyword.php">
		  <label for="PalabraClave">Por favor escriba la palabra a buscar:</label><br>
		  <input type="text" id="PalabraClave" name="PalabraClave"><br><br>
		  <span class="ayuda">Escriba una palabara para buscar</span>
		  <input type="submit" value="Buscar">
		</form>
		
		<hr>
		<form action="add_joke.php">
		  <label for="fname">Por favor escriba el nuevo Chiste:</label><br>
		  <input type="text" id="NuevoChiste" name="NuevoChiste"><br>
		   <span class="ayuda">Escriba la primera mitad del chiste aqui</span>
		  <br>
		  <label for="fname">Por favor escriba la palabra a buscar:</label><br>
		  <input type="text" id="NuevaRespuesta" name="NuevaRespuesta"><br>
		   <span class="ayuda">Escriba la segunda mitad del chiste</span>
		   
		  
		  <input type="submit" value="Agregar nuevo Chiste">
		</form>
		
		<?php
		//include "search_keyword.php";
		
		
		// Cerrar conexion
		mysqli_close($conn);
		//echo "<br> Conexion cerrada";
		?>
		
	</body>
</html>

