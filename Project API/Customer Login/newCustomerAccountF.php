<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../Database/connection.php';

$con = getConnection();

$data = json_decode(file_get_contents("php://input"));

$stmt = $con->prepare('CALL checkCustomerAccount(?, ?)');

$stmt->bindParam(1, $data->Email, PDO::PARAM_STR, 20);
$stmt->bindParam(2, $data->Password, PDO::PARAM_STR, 20);

$stmt->execute();

$rows = $stmt->rowCount();

if($rows > 0){
	echo "Account already exists.";
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	echo " Customer ID: ". $result['CustomerID'];
	
}

else{
	$stmt = $con->prepare('CALL addCustomerAccount(?, ?, ?, ?)');
	
	$stmt->bindParam(1, $data->Fname, PDO::PARAM_STR, 20);
	$stmt->bindParam(2, $data->Lname, PDO::PARAM_STR, 20);
	$stmt->bindParam(3, $data->Email, PDO::PARAM_STR, 20);
	$stmt->bindParam(4, $data->Password, PDO::PARAM_STR, 20);
	
	$stmt->execute();
	
	$stmt = $con->prepare('CALL checkCustomerAccount(?, ?)');

	$stmt->bindParam(1, $data->Email, PDO::PARAM_STR, 20);
	$stmt->bindParam(2, $data->Password, PDO::PARAM_STR, 20);

	$stmt->execute();
	
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

	echo "Customer ID: ". $result['CustomerID'];
	
}

?>