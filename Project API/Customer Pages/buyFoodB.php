<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../Database/connection.php';

$con = getConnection();

$data = json_decode(file_get_contents("php://input"));

$customerID = $data->CustomerID;
$movieID = $data->MovieID;
$roomNo = $data->RoomNo;
$dateTime = $data->DateTime;
$quantity = $data->Quantity;
$foodID = $data->FoodID;

$stmt = $con->prepare('CALL getOrderNumber(?, ?, ?)');

$stmt->bindParam(1, $customerID, PDO::PARAM_STR, 11);
$stmt->bindParam(2, $roomNo, PDO::PARAM_STR, 11);
$stmt->bindParam(3, $dateTime, PDO::PARAM_STR, 6);

$stmt->execute();

$rows = $stmt->rowCount();

if($rows > 0){
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$orderNumber = $row['Order_Number'];
	}
}

else{
	$stmt = $con->prepare('CALL createFoodOrder(?, ?, ?)');
	
	$stmt->bindParam(1, $customerID, PDO::PARAM_STR, 11);
	$stmt->bindParam(2, $roomNo, PDO::PARAM_STR, 11);
	$stmt->bindParam(3, $dateTime, PDO::PARAM_STR, 6);

	$stmt->execute();
	
	$stmt = $con->prepare('CALL getOrderNumber(?, ?, ?)');

	$stmt->bindParam(1, $customerID, PDO::PARAM_STR, 11);
	$stmt->bindParam(2, $roomNo, PDO::PARAM_STR, 11);
	$stmt->bindParam(3, $dateTime, PDO::PARAM_STR, 6);

	$stmt->execute();
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$orderNumber = $row['Order_Number'];
	}
}

$stmt = $con->prepare('CALL getFoodQuantity(?, ?, ?)');

$stmt->bindParam(1, $orderNumber, PDO::PARAM_STR, 11);
$stmt->bindParam(2, $customerID, PDO::PARAM_STR, 11);
$stmt->bindParam(3, $foodID, PDO::PARAM_STR, 11);

$stmt->execute();

$rows = $stmt->rowCount();

if($rows > 0){
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$existingQuantity = $row['Quantity'];
	}
	
	$totalQuantity = $existingQuantity + $quantity;
	
	$stmt = $con->prepare('CALL updateQuantity(?, ?, ?, ?)');
	
	$stmt->bindParam(1, $totalQuantity, PDO::PARAM_STR, 11);
	$stmt->bindParam(2, $orderNumber, PDO::PARAM_STR, 11);
	$stmt->bindParam(3, $customerID, PDO::PARAM_STR, 11);
	$stmt->bindParam(4, $foodID, PDO::PARAM_STR, 11);
	
	$stmt->execute();
}

else{
	$stmt = $con->prepare('CALL createContainsFoodOrder(?, ?, ?, ?)');
	
	$stmt->bindParam(1, $orderNumber, PDO::PARAM_STR, 11);
	$stmt->bindParam(2, $customerID, PDO::PARAM_STR, 11);
	$stmt->bindParam(3, $foodID, PDO::PARAM_STR, 11);
	$stmt->bindParam(4, $quantity, PDO::PARAM_STR, 11);
	
	$stmt->execute();
}

$stmt = $con->prepare('CALL getBookingCost(?, ?, ?, ?)');

$stmt->bindParam(1, $customerID, PDO::PARAM_STR, 11);
$stmt->bindParam(2, $movieID, PDO::PARAM_STR, 11);
$stmt->bindParam(3, $dateTime, PDO::PARAM_STR, 6);
$stmt->bindParam(4, $roomNo, PDO::PARAM_STR, 11);

$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$existingCost = $row['Cost'];
}

$stmt = $con->prepare('CALL getFoodPrice(?)');

$stmt->bindParam(1, $foodID, PDO::PARAM_STR, 11);

$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$price = $row['Price'];
}

$totalCost = $existingCost + $quantity * $price;

$stmt = $con->prepare('CALL updateBookingCost(?, ?, ?, ?, ?)');

$stmt->bindParam(1, $totalCost, PDO::PARAM_STR, 11);
$stmt->bindParam(2, $customerID, PDO::PARAM_STR, 11);
$stmt->bindParam(3, $movieID, PDO::PARAM_STR, 11);
$stmt->bindParam(4, $dateTime, PDO::PARAM_STR, 6);
$stmt->bindParam(5, $roomNo, PDO::PARAM_STR, 11);

$stmt->execute();

echo "Order Number: ". $orderNumber. " ";
echo "Price: $". $quantity * $price;

?>