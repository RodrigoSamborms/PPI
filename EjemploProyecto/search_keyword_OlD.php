<?php
	include "db_connect.php";
	$keywordfromform = $_GET["PalabraClave"];
	//echo $keywordfromform;
	
	//Buscar en la base de datos
	echo "<h2>Show all Jokes with the word '$keywordfromform;'</h2>";
	$sql = "SELECT JokeID, Joke_question, Joke_answer FROM Jokes_table WHERE Joke_question LIKE '%". $keywordfromform ."%'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  // sacar datos de cada fila
	  while($row = $result->fetch_assoc()) {
		echo "Joke_id: " . $row["JokeID"]. " - Joke Question: " . $row["Joke_question"]. " " . $row["Joke_answer"]. "<br>";
	  }
	} else {
	  echo "0 results";
	}
	
	$conn->close();
?>
<br>
<a href="index.php">Return to main page</a>
