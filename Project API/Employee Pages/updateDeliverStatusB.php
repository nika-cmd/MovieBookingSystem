<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: PUT");

include_once '../Database/connection.php';

$con = getConnection();
$updateCon = getConnection();
$foodOrderCon = getConnection();

$data = json_decode(file_get_contents("php://input"));

$email =$data->Email;
$password = $data->Password;
$orderNo = $data->OrderNo;
$customerID = $data->CustomerID;
$roomNo = $data->RoomNo;
$dateTime = $data->DateTime;
$deliverStatus = $data->DeliverStatus;

//Get employee ssn of the one currently modifying the deliver status
$stmt = $con->prepare('CALL getEmployeeSSN(?, ?)');

$stmt->bindParam(1, $email, PDO::PARAM_STR, 20);
$stmt->bindParam(2, $password, PDO::PARAM_STR, 20);

$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);

$ESSN = $result['SSN'];

//Update deliver status and employee ssn in food orders table
$stmt2 = $updateCon->prepare('CALL updateDeliverStatus(?, ?, ?, ?, ?, ?)');

	//Set deliver status and employee ssn according to what employee set it as
	if($deliverStatus == 'inProgress'){
		$inProgress = 1;
        $stmt2->bindParam(1, $ESSN, PDO::PARAM_STR, 20);
		$stmt2->bindParam(2, $inProgress, PDO::PARAM_STR, 2);
	}
	else if($deliverStatus == 'delivered'){
		$delivered = 2;
        $stmt2->bindParam(1, $ESSN, PDO::PARAM_STR, 20);
		$stmt2->bindParam(2, $delivered, PDO::PARAM_STR, 2);
	}
	else{
		$orderPlaced = 0;
		$null = NULL;
        $stmt2->bindParam(1, $null, PDO::PARAM_STR, 20);
		$stmt2->bindParam(2, $orderPlaced, PDO::PARAM_STR, 2);
	}

$stmt2->bindParam(3, $orderNo, PDO::PARAM_STR, 11);
$stmt2->bindParam(4, $customerID, PDO::PARAM_STR, 11);
$stmt2->bindParam(5, $roomNo, PDO::PARAM_STR, 11);
$stmt2->bindParam(6, $dateTime, PDO::PARAM_STR, 50);

$stmt2->execute();

//Display correct values
$stmt = $foodOrderCon->prepare('CALL getFoodOrders(?, ?, ?)');

$delivered = 2;

$stmt->bindParam(1, $delivered, PDO::PARAM_STR, 2);
$stmt->bindParam(2, date('Y-m-d H:i:s'), PDO::PARAM_STR, 50);
$stmt->bindParam(3, $email, PDO::PARAM_STR, 20);

$stmt->execute();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	//Output only the updated food order
	if($orderNo == $row['Order_Number']){
		echo "Edited this order in the database.\n";
		echo "Order Number: ". $row['Order_Number']."\n";
		echo "ESSN: ". $row['ESSN']."\n";

		if($row['Deliver_Status'] == 1){
			echo "Deliver Status: In Progress\n\n\n";
		}
		else if($row['Deliver_Status'] == 2){
			echo "Deliver Status: Delivered\n\n\n";
		}
		else{
			echo "Deliver Status: Order Placed\n\n\n";
		}
	}  
}

?>
