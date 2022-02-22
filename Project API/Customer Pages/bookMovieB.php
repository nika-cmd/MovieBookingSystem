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
$seats = explode(",", $data->Seats);

$stmt = $con->prepare('CALL getCustomerSeat(?, ?, ?)');

$stmt->bindParam(1, $roomNo, PDO::PARAM_STR, 11);
$stmt->bindParam(2, $customerID, PDO::PARAM_STR, 11);
$stmt->bindParam(3, $dateTime, PDO::PARAM_STR, 6);

$stmt->execute();

$rows = $stmt->rowCount();

if($rows == 0){

	$cost = sizeOf($seats) * 10;

	$no_of_seats = sizeOf($seats);
}

else{
	$stmt = $con->prepare('CALL getBookingCost(?, ?, ?, ?)');
	
	$stmt->bindParam(1, $customerID, PDO::PARAM_STR, 11);
	$stmt->bindParam(2, $movieID, PDO::PARAM_STR, 11);
	$stmt->bindParam(3, $dateTime, PDO::PARAM_STR, 6);
	$stmt->bindParam(4, $roomNo, PDO::PARAM_STR, 11);
	
	$stmt->execute();
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		$existingCost = $row['Cost'];
	}
	
	$totalCost = $existingCost + (sizeOf($seats) * 10);
	
	$no_of_seats = $rows + sizeOf($seats);
	
}

$stmt = $con->prepare('CALL getBookingCustomer(?, ?, ?, ?)');

$stmt->bindParam(1, $customerID, PDO::PARAM_STR, 11);
$stmt->bindParam(2, $movieID, PDO::PARAM_STR, 11);
$stmt->bindParam(3, $dateTime, PDO::PARAM_STR, 6);
$stmt->bindParam(4, $roomNo, PDO::PARAM_STR, 11);
	
$stmt->execute();

$rows = $stmt->rowCount();

if($rows > 0){
	$stmt = $con->prepare('CALL updateCostNofSeatsBooking(?, ?, ?, ?, ?, ?)');
	
	$stmt->bindParam(1, $totalCost, PDO::PARAM_STR, 11);
	$stmt->bindParam(2, $no_of_seats, PDO::PARAM_STR, 11);
	$stmt->bindParam(3, $customerID, PDO::PARAM_STR, 6);
	$stmt->bindParam(4, $movieID, PDO::PARAM_STR, 11);
	$stmt->bindParam(5, $dateTime, PDO::PARAM_STR, 11);
	$stmt->bindParam(6, $roomNo, PDO::PARAM_STR, 11);
	
	$stmt->execute();
}

else{
	$stmt = $con->prepare('CALL createBooking(?, ?, ?, ?, ?, ?)');
	
	$stmt->bindParam(1, $customerID, PDO::PARAM_STR, 6);
	$stmt->bindParam(2, $movieID, PDO::PARAM_STR, 11);
	$stmt->bindParam(3, $dateTime, PDO::PARAM_STR, 11);
	$stmt->bindParam(4, $cost, PDO::PARAM_STR, 11);
	$stmt->bindParam(5, $no_of_seats, PDO::PARAM_STR, 11);
	$stmt->bindParam(6, $roomNo, PDO::PARAM_STR, 11);
	
	$stmt->execute();
}

echo "Booked: ";

foreach($seats as $seatID){
	$stmt = $con->prepare('UPDATE Seat SET CustomerID = ? WHERE SeatID = ? AND Room_No = ? AND DateTime = ?');
	
	$stmt->bindParam(1, $customerID, PDO::PARAM_STR, 6);
	$stmt->bindParam(2, $seatID, PDO::PARAM_STR, 11);
	$stmt->bindParam(3, $roomNo, PDO::PARAM_STR, 11);
	$stmt->bindParam(4, $dateTime, PDO::PARAM_STR, 11);
	
	$stmt->execute();
	
	echo $seatID;
	
}


?>