<?php
//Subir_archivo.php

//Chachar variables
$nombre_real		=$_FILES['archivo']['name'];
$archivo_temporal	=$_FILES['archivo']['tmp_name'];

//Obtener extension
$arreglo			= explode(".", $nombre_real);
$len				= count($arreglo);
$pos				= $len - 1;
$extension			= $arreglo[$pos];

//Carpeta para guardar archvos
$carpeta	= "/home/rodrigo/html/archivos/";
//$carpeta	= "archivos/"; //problemas de permisos del servidor apache?

//Obtener nombre
$encriptado			= md5_file($archivo_temporal);
$nuevo_nombre		= "$encriptado.$extension";

echo "Nomre real:			$nombre_real <br>";
echo "Archivo Temporal:		$archivo_temporal <br>";
echo "Extension				$extension <br>";
echo "Encriptado:			$encriptado <br>";
echo "Nuevo nombre:			$nuevo_nombre <br>";

copy ($archivo_temporal, $carpeta.$nuevo_nombre);
?>
