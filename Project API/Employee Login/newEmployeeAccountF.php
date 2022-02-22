<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../Database/connection.php';

$con = getConnection();

$data = json_decode(file_get_contents("php://input"));

//Check if employee account already exists
$stmt = $con->prepare('CALL getEmployeeSSN(?, ?)');

$stmt->bindParam(1, $data->Email, PDO::PARAM_STR, 20);
$stmt->bindParam(2, $data->Password, PDO::PARAM_STR, 20);

$stmt->execute();

$rows = $stmt->rowCount();

if($rows > 0){
	echo "Account already exists.";
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	echo " Employee SSN: ". $result['SSN'];
	
}

else{
    //Get manager and theatreid based on work location input
    $stmt = $con->prepare('CALL getManagerAndTheatreInfo(?)');

    $stmt-> bindParam(1, $data->WorkLocation, PDO::PARAM_STR, 20);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);


    //Add employee and their information to the database if work location exists
	$stmt = $con->prepare('CALL addEmployeeAccount(?, ?, ?, ?, ?, ?, ?, ?, ?)');
	
    $stmt->bindParam(1, $data->SSN, PDO::PARAM_STR, 20);
	$stmt->bindParam(2, $data->Fname, PDO::PARAM_STR, 20);
	$stmt->bindParam(3, $data->Lname, PDO::PARAM_STR, 20);
    $stmt->bindParam(4, $data->DateOfBirth, PDO::PARAM_STR, 6);
    $stmt->bindParam(5, $data->Address, PDO::PARAM_STR, 50);
    $stmt->bindParam(6, $result['Mgr_SSN'], PDO::PARAM_STR, 11);
    $stmt->bindParam(7, $result['TheatreID'], PDO::PARAM_STR, 11);
	$stmt->bindParam(8, $data->Email, PDO::PARAM_STR, 50);
	$stmt->bindParam(9, $data->Password, PDO::PARAM_STR, 50);
	
	$stmt->execute();
	
    //Output ssn of added employee account
	$stmt = $con->prepare('CALL getEmployeeSSN(?, ?)');

	$stmt->bindParam(1, $data->Email, PDO::PARAM_STR, 20);
	$stmt->bindParam(2, $data->Password, PDO::PARAM_STR, 20);

	$stmt->execute();
	
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	echo "Employee SSN: ". $result['SSN'];
	
}

?>