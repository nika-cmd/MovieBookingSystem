<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../Database/connection.php';

$con = getConnection();
$con2 = getConnection();

$stmt = $con->prepare('CALL getAvailableMovieShowings(?)');

$stmt->bindParam(1, date('Y-m-d H:i:s'), PDO::PARAM_STR, 6);

$stmt->execute();

$movieShowing = array();
$movieShowing["Movie Showing"] = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$infoArray = array();
	$seatArray = array();
	
	extract($row);
	
	$ex = array(
		"Name" => $Name,
		"Genre" => $Genre,
		"Duration" => $Duration,
		"Date & Time" => $DateTime, 
		"Location" => $Location,
	);

	array_push($infoArray, $ex);
	
	$stmt2 = $con2->prepare('CALL getMovieShowingSeats(?, ?)');
	
	$stmt2->bindParam(1, $row['RoomNo'], PDO::PARAM_STR, 11);
	$stmt2->bindParam(2, $row['DateTime'], PDO::PARAM_STR, 6);
	
	$stmt2->execute();
	
	while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
		extract($row2);
		
		$ex2 = array(
			"Seat ID" => $SeatID,
			"Seat Type" => $Seat_Type
		);
		
		array_push($seatArray, $ex2);
	}

	$ex3 = array(
		"Information" => $infoArray,
		"Seats" => $seatArray
	);

	array_push($movieShowing["Movie Showing"], $ex3);
}

echo json_encode($movieShowing);

?>