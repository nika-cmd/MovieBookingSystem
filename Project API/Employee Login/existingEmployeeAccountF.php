<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../Database/connection.php';

$con = getConnection();

$email = isset($_GET['Email']) ? $_GET['Email'] : die();
$password = isset($_GET['Password']) ? $_GET['Password'] : die();

$stmt = $con->prepare('CALL getEmployeeInfo(?, ?)');

$stmt->bindParam(1, $email, PDO::PARAM_STR, 20);
$stmt->bindParam(2, $password, PDO::PARAM_STR, 20);

$stmt->execute();

$rows = $stmt->rowCount();

if($rows > 0){
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	echo " SSN: ". $result['SSN']. "\n";

    //Checking if ssn matches a manager ssn in the database
	$stmt = $con->prepare('CALL checkManagerSSN(?)');
	
	$stmt->bindParam(1, $result['SSN'], PDO::PARAM_STR, 20);
	
	$stmt->execute();

	$rows = $stmt->rowCount();
	
	if($rows == 0){
		echo "Employee Account";
	}
	
	else{
		echo "Manager Account";
	}
}

else{
	echo "Account doesn't exist.";
}

?>