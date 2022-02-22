<?php 
session_start();

include_once '../Database/connection.php';

$_SESSION["customerE-mail"] = $_POST["E-mail"];
$_SESSION["customerPassword"] = $_POST["Password"];

$email = $_POST["E-mail"];
$password = $_POST["Password"];

$con = getConnection();

$stmt = $con->prepare('CALL checkCustomerAccount(?, ?)');

$stmt-> bind_param('ss', $email, $password);  

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){
	mysqli_close($con);
	header("Location: ../Customer Pages/customerHomeF.php");
	
	exit();
}

else{
	sleep(5);
	header("Location: existingCustomerAccountF.php");
	exit();
}

mysqli_close($con);

?>