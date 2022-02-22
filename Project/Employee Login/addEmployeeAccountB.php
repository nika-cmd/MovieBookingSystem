<?php
session_start();
$_SESSION["employeeE-mail"] = $_POST["E-mail"];
$_SESSION["employeePasswor"] = $_POST["Password"];

$SSN = $_POST["SSN"];
$firstname = $_POST["Firstname"];
$lastname = $_POST["Lastname"];
$dateOfBirth = $_POST["DateofBirth"];
$address = $_POST["Address"];
$email = $_POST["E-mail"];
$password = $_POST["Password"];
$workLocation = $_POST["WorkLocation"];


//Make database connection
include_once '../Database/connection.php';
$con = getConnection();

$stmt = $con->prepare('CALL getEmployeeSSN(?, ?)');

$stmt-> bind_param('ss', $email, $password);  

$stmt->execute();

$result = $stmt->get_result();

//Display error message if account already exists
if($result->num_rows > 0){
	echo "Account already exists.";
	sleep(5);
	header("Location: newEmployeeAccountF.php");
	exit();
}

else{
	//Display error message if not all fields have inputs
	if(empty($_POST["SSN"]) || empty($_POST["Firstname"]) || empty($_POST["DateofBirth"]) || empty($_POST["Address"]) || empty($_POST["E-mail"])
			|| empty($_POST["Password"]) || empty($_POST["WorkLocation"]) ){
		echo "A field was not entered.";
		sleep(5);
		header("Location: newEmployeeAccountF.php");
		exit();
	}
	$con->next_result();

	//Get manager and theatreid based on work location input
	$stmt2 = $con->prepare('CALL getManagerAndTheatreInfo(?)');
	
	$stmt2-> bind_param('s', $workLocation);  
	
	$stmt2->execute();
	
	$result2 = $stmt2->get_result();
	
	//Print error message if work location does not exist
	if($result2->num_rows == 0){
		echo "This work location does not exist.";
		sleep(5);
		header("Location: newEmployeeAccountF.php");
		exit();
	}
	
	//Add employee and their information to the database if work location exists
	else{
		$value2 = $result2->fetch_object();
		$mgr_SSN = $value2->Mgr_SSN;
		$theatre_ID = $value2->TheatreID;
		
		$con->next_result();

		$stmt = $con->prepare('CALL addEmployeeAccount(?, ?, ?, ?, ?, ?, ?, ?, ?)');
		
		$stmt-> bind_param('sssssssss', $SSN, $firstname, $lastname, $dateOfBirth, $address, $mgr_SSN, $theatre_ID, $email, $password);  
	
		$stmt->execute();
	}
}

$con->next_result();
mysqli_close($con);

header("Location: ../Employee Pages/employeeHomeF.php");

?>