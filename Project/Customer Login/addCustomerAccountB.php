<?php 
session_start();

include_once '../Database/connection.php';

$_SESSION["customerE-mail"] = $_POST["E-mail"];
$_SESSION["customerPassword"] = $_POST["Password"];

$firstname = $_POST["Firstname"];
$lastname = $_POST["Lastname"];
$email = $_POST["E-mail"];
$password = $_POST["Password"];

$con = getConnection();

$stmt = $con->prepare('CALL checkCustomerAccount(?, ?)');

$stmt-> bind_param('ss', $email, $password);  

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0){
	echo "Account already exists.";
	sleep(5);
	header("Location: newCustomerAccountF.php");
	exit();
}

else{
	
	if(empty($_POST["Firstname"]) || empty($_POST["Lastname"]) || empty($_POST["E-mail"]) || empty($_POST["Password"])){
		echo "A field was not entered.";
		sleep(5);
		header("Location: newCustomerAccountF.php");
		exit();
	}
	
	$con->next_result();
	
	$stmt = $con->prepare('CALL addCustomerAccount(?, ?, ?, ?)');
	
	$stmt->bind_param('ssss', $firstname, $lastname, $email, $password);
	
	$stmt->execute();

}


header("Location: ../Customer Pages/customerHomeF.php");
exit();
 
mysqli_close($con);

?>