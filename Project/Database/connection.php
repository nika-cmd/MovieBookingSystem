<?php

function getConnection(){
	// Create connection
	$con=mysqli_connect("127.0.0.1","root","","");

	// Check connection
	if (mysqli_connect_errno($con)){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	date_default_timezone_set("America/Edmonton");
	
	return $con;
}


?>