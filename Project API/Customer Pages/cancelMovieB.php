<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

include_once '../Database/connection.php';

$con = getConnection();

$data = json_decode(file_get_contents("php://input"));

$customerID = $data->CustomerID;
$movieID = $data->MovieID;
$roomNo = $data->RoomNo;
$dateTime = $data->DateTime;

$stmt = $con->prepare('CALL cancelBookingSeats(?, ?, ?)');

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

$stmt = $con->prepare('CALL deleteCustomerFood(?, ?)');

$stmt->bindParam(1, $orderNumber, PDO::PARAM_STR, 11);
$stmt->bindParam(2, $customerID, PDO::PARAM_STR, 11);

$stmt->execute();

$stmt = $con->prepare('CALL deleteCustomerFoodOrder(?, ?, ?, ?)');

$stmt->bindParam(1, $orderNumber, PDO::PARAM_STR, 11);
$stmt->bindParam(2, $customerID, PDO::PARAM_STR, 11);
$stmt->bindParam(3, $roomNo, PDO::PARAM_STR, 11);
$stmt->bindParam(4, $dateTime, PDO::PARAM_STR, 6);

$stmt->execute();

$stmt = $con->prepare('CALL deleteBooking(?, ?, ?, ?)');

$stmt->bindParam(1, $customerID, PDO::PARAM_STR, 11);
$stmt->bindParam(2, $movieID, PDO::PARAM_STR, 11);
$stmt->bindParam(3, $dateTime, PDO::PARAM_STR, 6);
$stmt->bindParam(4, $roomNo, PDO::PARAM_STR, 11);

$stmt->execute();

echo "Booking Deleted Successfully.";

?>