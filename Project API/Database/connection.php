<?php

function getConnection(){

	$dsn = "mysql:host=127.0.0.1;dbname=cpsc471_project;";
	
	try {
		$pdo = new PDO($dsn, "root", "");
		$pdo->exec("set names utf8");
		
		if ($pdo) {
			
		}
		
	} catch (PDOException $e) {
		echo "Database could not be connected";
	}
	
	date_default_timezone_set("America/Edmonton");

	return $pdo;
}
	
?>