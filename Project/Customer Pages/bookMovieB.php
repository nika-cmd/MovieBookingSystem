<?php
session_start();

include_once '../Database/connection.php';

//Get the customer's email and password using Session
$email = $_SESSION["customerE-mail"];
$password = $_SESSION["customerPassword"];

//Get the Room # and the Date and Time that a user is booking a seat for
$roomNo = $_POST['RoomNo'];
$dateTime = $_POST['DateTime'];

//If the user does not select any seats...
if(sizeOf($_POST['seats']) == 0){
	//Return the user to the home page
	header("Location: customerHomeF.php");
	exit();
}

$con = getConnection();

//Get the customer ID of the customer
$stmt = $con->prepare('CALL checkCustomerAccount(?, ?)');

$stmt-> bind_param('ss', $email, $password);  

$stmt->execute();

$result = $stmt->get_result();

//Set the customer ID
while($row = mysqli_fetch_array($result)){
	$customerID = $row['CustomerID'];
}

$con->next_result();

//Get the MovieID of the movie that the user is booking for
$stmt2 = $con->prepare('CALL getMovieID(?, ?)');

$stmt2-> bind_param('ss', $dateTime, $roomNo);  

$stmt2->execute();

$result2 = $stmt2->get_result();

//Set the MovieID
while($row2 = mysqli_fetch_array($result2)){
	$movieID = $row2['MovieID'];
}

$con->next_result();

//Check if the user has already booked a seat for this specific movie showing
$stmt3 = $con->prepare('CALL getCustomerSeat(?, ?, ?)');

$stmt3-> bind_param('sss', $roomNo, $customerID, $dateTime);

$stmt3->execute();

$result3 = $stmt3->get_result();

$con->next_result();

//If the user has not already booked a different seat for this movie showing...
if($result3->num_rows == 0){
	//Get the cost of only the current amount of seats that the user is booking for (current amount of seats * $10)
	$cost = sizeOf($_POST['seats']) * 10;
	//Get only the current number of seats that the customer is booking for
	$no_of_seats = sizeOf($_POST['seats']);
}

//If the user has already booked a different seat for this movie showing...
else{
	//Get the previous cost of the seats that the customer has already booked
	$stmt4 = $con->prepare('CALL getBookingCost(?, ?, ?, ?)');

	$stmt4-> bind_param('ssss', $customerID, $movieID, $dateTime, $roomNo);  

	$stmt4->execute();

	$result4 = $stmt4->get_result();
	
	$con->next_result();
	
	//Get the existing cost of the booking
	while($row4 = mysqli_fetch_array($result4)){
		$existingCost = $row4['Cost'];
	}
	
	//Add the existing cost of the booking to the current amount of seats the user is booking
	$totalCost = $existingCost + (sizeOf($_POST['seats']) * 10);
	
	//Get the existing number of seats the user has booked and add it to the current amount of seats the user is booking
	$no_of_seats = $result3->num_rows + sizeOf($_POST['seats']);
}

//Check if a user has already booked for this specific movie showing
$stmt5 = $con->prepare('CALL getBookingCustomer(?, ?, ?, ?)');

$stmt5->bind_param('ssss', $customerID, $movieID, $dateTime, $roomNo);
	
$stmt5->execute();

$result5 = $stmt5->get_result();

$con->next_result();

//If the user has already booked this specific movie showing...
if($result5->num_rows > 0){
	//Only update the cost and the # of seats of this booking
	$stmt6 = $con->prepare('CALL updateCostNofSeatsBooking(?, ?, ?, ?, ?, ?)');

	$stmt6->bind_param('ssssss', $totalCost, $no_of_seats, $customerID, $movieID, $dateTime, $roomNo);
	
	$stmt6->execute();
	
	$con->next_result();
}

//If the user has not already booked this specific movie showing...
else{
	$stmt6 = $con->prepare('CALL createBooking(?, ?, ?, ?, ?, ?)');

	$stmt6->bind_param('ssssss', $customerID, $movieID, $dateTime, $cost, $no_of_seats, $roomNo);
	
	$stmt6->execute();
	
	$con->next_result();
}

//For each seat that the user has selected...
foreach ($_POST['seats'] as $select){
	//Update the seats in the seat table that the user is booking
	$stmt7 = $con->prepare('UPDATE Seat SET CustomerID = ? WHERE SeatID = ? AND Room_No = ? AND DateTime = ?');
	
	$stmt7->bind_param('ssss', $customerID, $select, $roomNo, $dateTime);
	
	$stmt7->execute();
	
	$con->next_result();
}

header("Location: customerAccountInfoF.php");
exit();

?>