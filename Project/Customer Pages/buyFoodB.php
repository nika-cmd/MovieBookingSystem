<?php
session_start();

include_once '../Database/connection.php';

//Get the email and password of the customer using Session
$email = $_SESSION["customerE-mail"];
$password = $_SESSION["customerPassword"];

//Get the quantity, date and time, room #, and food ID from the submitted form
$quantity = $_POST['quantity'];
$dateTime = $_POST['DateTime'];
$roomNo = $_POST['Room_No'];
$foodID = $_POST['FoodID'];

//If the quantity entered is 0...
if($_POST['quantity'] == 0){
	//Return the user back to the Food page
	header("Location: customerFoodF.php");
	exit();
}

$con = getConnection();

//Get the customer ID of the customer
$stmt = $con->prepare('CALL checkCustomerAccount(?, ?)');

$stmt-> bind_param('ss', $email, $password);  

$stmt->execute();

$result = $stmt->get_result();

//Set the customer ID of the customer
while($row = mysqli_fetch_array($result)){
	$customerID = $row['CustomerID'];
}

$con->next_result();

//Get the movie ID of the movie that the user is buying food for
$stmt2 = $con->prepare('CALL getMovieID(?, ?)');

$stmt2-> bind_param('ss', $dateTime, $roomNo);  

$stmt2->execute();

$result2 = $stmt2->get_result();

$con->next_result();

//Set the movie ID 
while($row2 = mysqli_fetch_array($result2)){
	$movieID = $row2['MovieID'];
}

//Check if the customer has already bought food for this movie showing
$stmt3 = $con->prepare('CALL getOrderNumber(?, ?, ?)');

$stmt3-> bind_param('sss', $customerID, $roomNo, $dateTime);  

$stmt3->execute();

$result3 = $stmt3->get_result();

$con->next_result();

//If the customer has already bought food for this movie showing...
if($result3->num_rows > 0){
	//Set the order number
	while($row3 = mysqli_fetch_array($result3)){
		$orderNumber = $row3['Order_Number'];
	}
}

//If the customer has not already bought food for this movie showing...
else{
	//Create a new food order for this movie showing
	$stmt4 = $con->prepare('CALL createFoodOrder(?, ?, ?)');

	$stmt4-> bind_param('sss', $customerID, $roomNo, $dateTime);  

	$stmt4->execute();
	
	$con->next_result();
	
	//Get the order number for this newly created food order
	$stmt5 = $con->prepare('CALL getOrderNumber(?, ?, ?)');

	$stmt5-> bind_param('sss', $customerID, $roomNo, $dateTime);  

	$stmt5->execute();

	$result5 = $stmt5->get_result();
	
	$con->next_result();
	
	//Set the order number
	while($row5 = mysqli_fetch_array($result5)){
		$orderNumber = $row5['Order_Number'];
	}
}

//Check if the customer already has bought this specific food for this movie showing
$stmt6 = $con->prepare('CALL getFoodQuantity(?, ?, ?)');

$stmt6-> bind_param('sss', $orderNumber, $customerID, $foodID);  

$stmt6->execute();

$result6 = $stmt6->get_result();

$con->next_result();

//If the customer has already bought this specific food for this movie showing...
if($result6->num_rows > 0){
	//Get the existing quantity of this specific food
	while($row6 = mysqli_fetch_array($result6)){
		$existingQuantity = $row6['Quantity'];
	}
	
	//Add the existing amount of this food to the amount the customer is now requesting
	$totalQuantity = $quantity + $existingQuantity;
	
	//Update the quantity of food the user is ordering
	$stmt7 = $con->prepare('CALL updateQuantity(?, ?, ?, ?)');

	$stmt7-> bind_param('ssss', $totalQuantity, $orderNumber, $customerID, $foodID);  

	$stmt7->execute();
	
	$con->next_result();
}

//If the customer has not already bought this specific food for this movie showing...
else{
	//Add the new food that the customer is ordering into the contains_food_order table
	$stmt8 = $con->prepare('CALL createContainsFoodOrder(?, ?, ?, ?)');

	$stmt8-> bind_param('ssss', $orderNumber, $customerID, $foodID, $quantity);  

	$stmt8->execute();
	
	$con->next_result();
}

//Get the previous cost of the booking of this movie showing
$stmt9 = $con->prepare('CALL getBookingCost(?, ?, ?, ?)');

$stmt9-> bind_param('ssss', $customerID, $movieID, $dateTime, $roomNo);  

$stmt9->execute();

$result9 = $stmt9->get_result();

$con->next_result();

//Set the existing cost of this movie showing
while($row9 = mysqli_fetch_array($result9)){
	$existingCost = $row9['Cost'];
}

//Get the price of the food the customer is now ordering
$stmt10 = $con->prepare('CALL getFoodPrice(?)');

$stmt10-> bind_param('s', $foodID);  

$stmt10->execute();

$result10 = $stmt10->get_result();

$con->next_result();

//Set the price of the food the customer is now ordering
while($row10 = mysqli_fetch_array($result10)){
	$price = $row10['Price'];
}

//Add the existing cost of the booking to the current quantity * the price of the food the customer is currently ordering
$totalCost = $existingCost + $quantity * $price;

//Update the cost of the booking of this specific movie showing
$stmt11 = $con->prepare('CALL updateBookingCost(?, ?, ?, ?, ?)');

$stmt11-> bind_param('sssss', $totalCost, $customerID, $movieID, $dateTime, $roomNo);  

$stmt11->execute();

$con->next_result();

header("Location: customerAccountInfoF.php");
exit();

?>