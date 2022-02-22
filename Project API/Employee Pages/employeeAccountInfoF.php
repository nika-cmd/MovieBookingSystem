<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../Database/connection.php';

$con = getConnection();

$email = isset($_GET['Email']) ? $_GET['Email'] : die();
$password = isset($_GET['Password']) ? $_GET['Password'] : die();

$stmt = $con->prepare('CALL getEmployeeInfo(?, ?)');

$stmt->bindParam(1, $email, PDO::PARAM_STR, 20);
$stmt->bindParam(2, $password, PDO::PARAM_STR, 20);

$stmt->execute();

$rows = $stmt->rowCount();

if($rows > 0){
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	echo " Email: ". $result['Email_Address']. "\n";
    echo " Fname: ". $result['First_Name']. "\n";
    echo " Lname ". $result['Last_Name']. "\n";
    echo " Date of Birth: ". $result['DOB']. "\n";
    echo " Address: ". $result['Address']. "\n";
    echo " TheatreID: ". $result['TheatreID']. "\n";
}

else{
	echo "Account doesn't exist.";
}

?>