<?php

include_once("ewcfg13.php");

global $EW_CONN;

$mysqli = mysqli_connect($EW_CONN["DB"]["host"], $EW_CONN["DB"]["user"], $EW_CONN["DB"]["pass"], $EW_CONN["DB"]["db"]);

if (!$mysqli) {
	echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
	echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
	echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
	exit;
}

?>





