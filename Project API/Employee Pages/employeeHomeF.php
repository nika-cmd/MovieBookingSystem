<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../Database/connection.php';

$con = getConnection();
$seatCon = getConnection();
$foodCon = getConnection();

$email = isset($_GET['Email']) ? $_GET['Email'] : die();

//Get order_numbers of customers in the same theatre employee works in
//Ordered by time and prioritizes orders that are in progress
$stmt = $con->prepare('CALL getFoodOrders(?, ?, ?)');

$delivered = 2;

$stmt->bindParam(1, $delivered, PDO::PARAM_STR, 1);
$stmt->bindParam(2, date('Y-m-d H:i:s'), PDO::PARAM_STR, 50);
$stmt->bindParam(3, $email, PDO::PARAM_STR, 20);

$stmt->execute();

$foodOrder = array();
$foodOrder["Food Orders"] = array();

while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $seatArray = array();
    $foodArray = array();

    //Get seatids for associated with the food order
    $seatStmt = $seatCon->prepare('CALL getSeatIDs(?, ?)');

    $seatStmt->bindParam(1, $row['CustomerID'], PDO::PARAM_STR, 11);
    $seatStmt->bindParam(2, $row['DateTime'], PDO::PARAM_STR, 50);

    $seatStmt->execute();

    while($seatRow = $seatStmt->fetch(PDO::FETCH_ASSOC)){
        extract($seatRow);
        $ex2 = array(
            "Seat ID" => $SeatID
        );

        array_push($seatArray, $ex2);
    }

    $foodStmt = $foodCon->prepare('CALL getFoodItemsbyOrderNo(?)');

    $foodStmt->bindParam(1, $row['Order_Number'], PDO::PARAM_STR, 11);

    $foodStmt->execute();

    //Get Food items based on the order number
    while($foodRow = $foodStmt->fetch(PDO::FETCH_ASSOC)){
        extract($foodRow);

        //Convert food item to a string
        $foodItem = 'none';
        if($Popcorn == 1){
            $foodItem = 'Popcorn';
        }
        else if($Drink == 1){
            $foodItem = 'Drink';
        }
        else if($Candy == 1){
            $foodItem = 'Candy';
        }
        else if($Poutine == 1){
            $foodItem = 'Poutine';
        }
        else if($Nacho == 1){
            $foodItem = 'Nacho';
        }

        $ex3 = array(
            "Food" => $foodItem,
            "Description" => $Description,
            "Size" => $Size,
            "Quantity" => $Quantity
        );

        array_push($foodArray, $ex3);
    }

    extract($row);

    if($Deliver_Status == 0){
        $ds = 'Order Placed';
    }
    else if($Deliver_Status == 1){
        $ds = 'In Progress';
    }
    else {
        $ds = 'Delivered';
    }
    $ex = array(
        "Order Number" => $Order_Number,
        "Deliver Status" => $ds,
        "Date Time" => $DateTime,
        "Room Number" => $Room_No,
        "Seat" => $seatArray,
        "Food" => $foodArray
    );

    array_push($foodOrder["Food Orders"], $ex);
}
echo json_encode($foodOrder);

?>