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

//Get the order_number of the food that the customer has ordered for this movie showing
$stmt3 = $con->prepare('CALL getOrderNumber(?, ?, ?)');

$stmt3-> bind_param('sss', $customerID, $roomNo, $dateTime);  

$stmt3->execute();

$result3 = $stmt3->get_result();

$con->next_result();

//Set the order number
while($row3 = mysqli_fetch_array($result3)){
	$orderNumber = $row3['Order_Number'];
}

//Get the total cost of all the food the customer has ordered for this booking
$stmt4 = $con->prepare('CALL getAllFoodCost(?, ?)');

$stmt4-> bind_param('ss', $orderNumber, $customerID);  

$stmt4->execute();

$result4 = $stmt4->get_result();

$con->next_result();

//Calculate the total cost of all the food the customer has ordered for this booking
while($row4 = mysqli_fetch_array($result4)){
	$price = $row4['Price'];
	$quantity = $row4['Quantity'];
	$cost = $cost + $price * $quantity;
}

//Get the total cost of this booking
$stmt5 = $con->prepare('CALL getBookingCost(?, ?, ?, ?)');

$stmt5-> bind_param('ssss', $customerID, $movieID, $dateTime, $roomNo);  

$stmt5->execute();

$result5 = $stmt5->get_result();

$con->next_result();
	
//Set the total cost of this booking
while($row5 = mysqli_fetch_array($result5)){
	$existingCost = $row5['Cost'];
}

//Calculate the cost of the booking with the food removed
$totalCost = $existingCost - $cost;

//Set the cost of the booking to be only the cost of the seats (without the food)
$stmt6 = $con->prepare('CALL updateBookingCost(?, ?, ?, ?, ?)');

$stmt6->bind_param('sssss', $totalCost, $customerID, $movieID, $dateTime, $roomNo);
	
$stmt6->execute();

$con->next_result();

//Delete all food orders for this customer for this movie showing
$stmt7 = $con->prepare('CALL deleteCustomerFood(?, ?)');
	
$stmt7->bind_param('ss', $orderNumber, $customerID);
	
$stmt7->execute();

$con->next_result();

//Delete the food order for this customer for this movie showing
$stmt8 = $con->prepare('CALL deleteCustomerFoodOrder(?, ?, ?, ?)');
	
$stmt8->bind_param('ssss', $orderNumber, $customerID, $roomNo, $dateTime);
	
$stmt8->execute();

header("Location: customerAccountInfoF.php");
exit();

?>