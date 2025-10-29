<?php
	include "db_connect.php";
	$new_joke_question = $_GET["NuevoChiste"];
	$new_joke_answer = $_GET["NuevaRespuesta"];
	
	//manejando entradas con ' o " en sql
	$new_joke_question = addslashes($new_joke_question);
	$new_joke_answer = addslashes($new_joke_answer);
	
	//Buscar en la base de datos
	echo "<h2>Intentando agrear un nuevo Chiste: $new_joke_question and $new_joke_answer</h2>";
	
	$sql = "INSERT INTO Jokes_table (JokeID, Joke_question, Joke_answer) VALUES (NULL, '$new_joke_question', '$new_joke_answer')";
	//$mysqli->query($sql) or die(mysqli_error($mysqli)); //ejemplo de manejo de errores de los videos
	// Ejecutar la consulta
	if ($conn->query($sql) === TRUE) {
		echo "Lista de Chistes";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
	echo "<br>";
	
	include "search_all_jokes.php"
	
	
	//$conn->close();
?>
<br>
<a href="index.php">Return to main page</a>
