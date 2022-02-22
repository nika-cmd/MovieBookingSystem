<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../Database/connection.php';

$con = getConnection();
$con2 = getConnection();
$con3 = getConnection();
$con4 = getConnection();
$con5 = getConnection();

$email = isset($_GET['Email']) ? $_GET['Email'] : die();
$password = isset($_GET['Password']) ? $_GET['Password'] : die();

$stmt = $con->prepare('CALL getCustomerInfo(?, ?)');

$stmt->bindParam(1, $email, PDO::PARAM_STR, 20);
$stmt->bindParam(2, $password, PDO::PARAM_STR, 20);

$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$customerID = $row['CustomerID'];
}

$stmt2 = $con2->prepare('CALL getCustomerBookings(?, ?)');

$stmt2->bindParam(1, date('Y-m-d'), PDO::PARAM_STR, 6);
$stmt2->bindParam(2, $customerID, PDO::PARAM_STR, 11);

$stmt2->execute();

$showingFood = array();
$showingFood["Booking"]["Movie Showings"] = array();


while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
	$movieShowingArray = array();
	$seatArray = array();
	$foodArray = array();

	extract($row2);
	
	$ex = array(
		"Date & Time" => $DateTime,
		"Name" => $Name,
		"Genre" => $Genre,
		"Duration" => $Duration,
		"Cost" => $Cost
	);
	
	$stmt3 = $con3->prepare('CALL getCustomerSeat(?, ?, ?)');
	
	$stmt3->bindParam(1, $Room_No, PDO::PARAM_STR, 11);
	$stmt3->bindParam(2, $customerID, PDO::PARAM_STR, 11);
	$stmt3->bindParam(3, $DateTime, PDO::PARAM_STR, 6);
	
	$stmt3->execute();
	
	while($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)){
		extract($row3);
		
		$ex2 = array(
			"Seat ID" => $SeatID,
			"Seat Type" => $Seat_Type
		);
		
		array_push($seatArray, $ex2);
	}
	
	$stmt4 = $con4->prepare('CALL getCustomerFood(?, ?, ?)');
	
	$stmt4->bindParam(1, $customerID, PDO::PARAM_STR, 11);
	$stmt4->bindParam(2, $Room_No, PDO::PARAM_STR, 11);
	$stmt4->bindParam(3, $DateTime, PDO::PARAM_STR, 6);
	
	$stmt4->execute();

	unset($allFoodID);
	unset($allFoodQuantity);

	while($row4 = $stmt4->fetch(PDO::FETCH_ASSOC)){
		extract($row4);
		$allFoodID[] = $row4["FoodID"];
		$allFoodQuantity[] = $row4["Quantity"];
	}
	if($allFoodID != null){
		foreach(array_combine($allFoodID, $allFoodQuantity) as $id => $quant){

			$stmt5 = $con5->prepare('CALL getFoodByID(?)');
			
			$stmt5->bindParam(1, $id, PDO::PARAM_STR, 11);
			
			$stmt5->execute();

			while($row5 = $stmt5->fetch(PDO::FETCH_ASSOC)){
				extract($row5);
					
				$ex3 = array(
						
					"Description" => $Description,
					"Size" => $Size,
					"Quantity" => $quant,
					"Price" => $Price
				);
				array_push($foodArray, $ex3);
			}
		}
	}
	
	array_push($movieShowingArray, $ex);

	$ex4 = array(
		"Information" => $movieShowingArray,
		"Seat" => $seatArray,
		"Food" => $foodArray
	);

	array_push($showingFood["Booking"]["Movie Showings"], $ex4);
}

echo json_encode($showingFood);

?>