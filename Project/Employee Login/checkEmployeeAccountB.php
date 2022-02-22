<?php 
session_start();
$_SESSION["employeeE-mail"] = $_POST["E-mail"];
$_SESSION["employeePassword"] = $_POST["Password"];

$email = $_POST["E-mail"];
$password = $_POST["Password"];

//Make database connection
include_once '../Database/connection.php';
$con = getConnection();

//Checking if email and password matches an employee account in the database
$stmt = $con->prepare('CALL getEmployeeSSN(?, ?)');

$stmt-> bind_param('ss', $email, $password);  

$stmt->execute();

$result = $stmt->get_result();

//If account exists continue
if($result->num_rows > 0){
	
	$value = $result->fetch_object();
	
	$SSN = $value->SSN;

	$con->next_result();
	
	//Checking if ssn matches a manager ssn in the database
	$stmt = $con->prepare('CALL checkManagerSSN(?)');
	
	$stmt-> bind_param('s', $SSN);  
	
	$stmt->execute();
	
	$result = $stmt->get_result();

	$con->next_result();
	
	if($result->num_rows == 0){
		header("Location: ../Employee Pages/employeeHomeF.php");
		mysqli_close($con);
		exit();
	}
	
	else{
		//header("Location: ../Manager Pages/editMovieShowingF.php");
		header("Location: ../Manager Pages/managerAccountInfoF.php");
		mysqli_close($con);
		exit();
	}

}
//Display error message if account does not exist
else{
	echo "Account does not exist.";
	sleep(5);
	header("Location: existingEmployeeAccount.php");
	exit();
}

 
mysqli_close($con);

?>