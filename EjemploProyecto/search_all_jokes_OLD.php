<?php
	// ERROR conexion no se pudo realizar
		if (!$conn) {
	    		die("Connection failed: " . mysqli_connect_error());
		}
		echo $conn->host_info . "<br>";
		echo "Conectado exitosamente\n" . "<br>";

		// Acciones sobre la base de datos aqui...
		$sql = "SELECT JokeID, Joke_question, Joke_answer FROM Jokes_table";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		  // sacar datos de cada fila
		  while($row = $result->fetch_assoc()) {
			echo "Joke_id: " . $row["JokeID"]. " - Joke Question: " . $row["Joke_question"]. " " . $row["Joke_answer"]. "<br>";
		  }
		} else {
		  echo "0 results";
		}

?>


