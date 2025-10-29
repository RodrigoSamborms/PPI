<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Accordion - Default functionality</title>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
  <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
  <script>
  $( function() {
	$( "#accordion" ).accordion();
  } );
  </script>	
  
  <style>
	* {
		font-family:Arial, Helvetica, sans-serrif;
	}
  </style>
  
</head>

<?php
	include "db_connect.php";
	$keywordfromform = $_GET["PalabraClave"];
	//echo $keywordfromform;
	
	//Buscar en la base de datos
	echo "<h1>Mostrar todos los chistes con la palabra: '$keywordfromform;'</h1>";
	$sql = "SELECT JokeID, Joke_question, Joke_answer FROM Jokes_table WHERE Joke_question LIKE '%". $keywordfromform ."%'";
	$result = $conn->query($sql);
?>

<div id="accordion">
	
<?php
	if ($result->num_rows > 0) {
	  // sacar datos de cada fila
	  while($row = $result->fetch_assoc()) {
		//echo "Joke_id: " . $row["JokeID"]. " - Joke Question: " . $row["Joke_question"]. " " . $row["Joke_answer"]. "<br>";
		echo "<h3>" . $row["Joke_question"]. " </h3>";
		echo"<div><p>" . $row["Joke_answer"]. "</p></div>";
	  }
	} else {
	  echo "0 results";
	}
	
	$conn->close();
?>

</div>

<br>
<a href="index.php">Return to main page</a>
