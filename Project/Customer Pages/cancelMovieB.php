<?php
session_start();

include_once '../Database/connection.php';

//Get the email and password of this customer using Session
$email = $_SESSION["customerE-mail"];
$password = $_SESSION["customerPassword"];

//Get the room # and date and time of the submitted booking to be canacelled
$roomNo = $_POST['RoomNo'];
$dateTime = $_POST['DateTime'];

$con = getConnection();

//Get the customer ID of the user
$stmt = $con->prepare('CALL checkCustomerAccount(?, ?)');

$stmt-> bind_param('ss', $email, $password);  

$stmt->execute();

$result = $stmt->get_result();

//Set the customer ID of the user
while($row = mysqli_fetch_array($result)){
	$customerID = $row['CustomerID'];
}

$con->next_result();

//Get the movie ID of the booking the customer is cancelling for
$stmt2 = $con->prepare('CALL getMovieID(?, ?)');

$stmt2-> bind_param('ss', $dateTime, $roomNo);  

$stmt2->execute();

$result2 = $stmt2->get_result();

//Set the movie ID 
while($row2 = mysqli_fetch_array($result2)){
	$movieID = $row2['MovieID'];
}

$con->next_result();

//Update all seats that this customer has booked for this movie showing (set them to NULL)
$stmt3 = $con->prepare('CALL cancelBookingSeats(?, ?, ?)');
	
$stmt3->bind_param('sss', $customerID, $roomNo, $dateTime);
	
$stmt3->execute();

$con->next_result();

//Get the order_number of the food that the customer has ordered for this movie showing
$stmt4 = $con->prepare('CALL getOrderNumber(?, ?, ?)');

$stmt4-> bind_param('sss', $customerID, $roomNo, $dateTime);  

$stmt4->execute();

$result4 = $stmt4->get_result();

$con->next_result();

//Set the order number
while($row4 = mysqli_fetch_array($result4)){
	$orderNumber = $row4['Order_Number'];
}

//Delete all food orders for this customer for this movie showing
$stmt5 = $con->prepare('CALL deleteCustomerFood(?, ?)');
	
$stmt5->bind_param('ss', $orderNumber, $customerID);
	
$stmt5->execute();

$con->next_result();

//Delete the food order for this customer for this movie showing
$stmt6 = $con->prepare('CALL deleteCustomerFoodOrder(?, ?, ?, ?)');
	
$stmt6->bind_param('ssss', $orderNumber, $customerID, $roomNo, $dateTime);
	
$stmt6->execute();

$con->next_result();

//Delete the booking for this movie showing for this customer
$stmt7 = $con->prepare('CALL deleteBooking(?, ?, ?, ?)');
	
$stmt7->bind_param('ssss', $customerID, $movieID, $dateTime, $roomNo);
	
$stmt7->execute();

$con->next_result();


header("Location: customerAccountInfoF.php");
exit();

?>