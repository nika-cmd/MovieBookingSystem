<?php
	session_start();

	//Get the email and password of the customer using Session
	$email = $_SESSION["employeeE-mail"];
	$password = $_SESSION["employeePassword"];

	//Get order number, customer id, movie id and date and time from the submitted form
	$orderNo = $_POST['OrderNo'];
	$customerID = $_POST['CustomerID'];
	$roomNo = $_POST['RoomNo'];
	$dateTime = $_POST['DateTime'];
	$deliverStatus = $_POST['deliverStatus'];

	//Make database connection
	include_once '../Database/connection.php';
	$con = getConnection();

	//Get SSN of employee editing the deliver status
	$stmt = $con->prepare('CALL getEmployeeSSN(?, ?)');

	$stmt-> bind_param('ss', $email, $password);  

	$stmt->execute();

	$result = $stmt->get_result();

	//Set employee SSN
	while($row = mysqli_fetch_array($result)){
		$ESSN = $row['SSN'];
	}

	$con->next_result();

	//Updating the deliver status and adding employee SSN to food order
	$stmt2 = $con->prepare('CALL updateDeliverStatus(?, ?, ?, ?, ?, ?)');

	//Set deliver status according to what employee set it as
	if($deliverStatus == 'inProgress'){
		$inProgress = 1;
		$stmt2-> bind_param('ssssss', $ESSN, $inProgress, $orderNo, $customerID, $roomNo, $dateTime); 
	}
	else if($deliverStatus == 'delivered'){
		$delivered = 2;
		$stmt2-> bind_param('ssssss', $ESSN, $delivered, $orderNo, $customerID, $roomNo, $dateTime); 
	}
	else{
		$orderPlaced = 0;
		$null = NULL;
		$stmt2-> bind_param('ssssss', $null, $orderPlaced, $orderNo, $customerID, $roomNo, $dateTime); 
	}

	$stmt2->execute();

	$con->next_result();

	mysqli_close($con);
	
	header("Location: employeeHomeF.php");
	exit();
?>