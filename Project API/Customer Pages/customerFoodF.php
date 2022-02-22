<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../Database/connection.php';

$con = getConnection();
$con2 = getConnection();

$email = isset($_GET['Email']) ? $_GET['Email'] : die();
$password = isset($_GET['Password']) ? $_GET['Password'] : die();

$stmt = $con->prepare('CALL checkCustomerAccount(?, ?)');

$stmt->bindParam(1, $email, PDO::PARAM_STR, 20);
$stmt->bindParam(2, $password, PDO::PARAM_STR, 20);

$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

$customerID = $result['CustomerID'];

$stmt = $con->prepare('CALL getMovieShowingFood(?, ?)');

$stmt->bindParam(1, date('Y-m-d H:i:s'), PDO::PARAM_STR, 6);
$stmt->bindParam(2, $customerID, PDO::PARAM_STR, 11);

$stmt->execute();

$movieFood = array();
$movieFood["Movie Showing"] = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$infoArray = array();
	$foodArray = array();
	
	extract($row);
	
	$ex = array(
		"Date & Time" => $DateTime, 
		"Name" => $Name, 
		"Room No" => $Room_No
	);
	
	array_push($infoArray, $ex);
	
	$stmt2 = $con2->prepare('CALL getAllFood()');
	$stmt2->execute();
	
	while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
		extract($row2);
		
		$ex2 = array(
			
			"Description" => $Description,
			"Size" => $Size,
			"Price" => $Price
		);
		
		array_push($foodArray, $ex2);
	}
	
	$ex3 = array(
		"Information" => $infoArray,
		"Food" => $foodArray
	);
	
	array_push($movieFood["Movie Showing"], $ex3);
}

echo json_encode($movieFood);

?>