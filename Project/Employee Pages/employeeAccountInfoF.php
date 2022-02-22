<?php
session_start();
?>

<html>

<header>
	
	<link rel="stylesheet" type="text/css" href="employeeAccountInfoF_styles.css">

	<title></title>

</header>

<body>
	<nav class ="navbar" id = "navbar">
		<p1> Website </p1>
		<ul>

			<li> <a href="employeeHomeF.php"> Home </a> </li>
			<li> <a href="employeeAccountInfoF.php"> Account</a> </li>
		</ul>
	</nav>
	
    <h1>Account Information</h1>
    <div>
		<?php
			
			//Make database connection
			include_once '../Database/connection.php';
			$con = getConnection();
			
			//Get employee info from the database
			$stmt = $con->prepare('CALL getEmployeeInfo(?, ?)');
			
			$email = $_SESSION["employeeE-mail"];
			$password = $_SESSION["employeePassword"];

			$stmt-> bind_param('ss', $email, $password);  

			$stmt->execute();

			$result = $stmt->get_result();
			
			$value = $result->fetch_object();
			
			//Display employee info
			echo "<ul class='info'>";
			
			echo "<li>First Name: ". $value->First_Name . "</li>";
			echo "<li>Last Name: ". $value->Last_Name . "</li>";
			echo "<li>Birthdate: ". $value->DOB . "</li>";
			echo "<li>Address: ". $value->Address . "</li>";
			echo "<li>E-mail: ". $_SESSION["employeeE-mail"]. "</li>";
			echo "<li>TheatreID: ". $value->TheatreID . "</li>";
			
			echo "</ul>";

			$con->next_result();

			mysqli_close($con);
		
		?>
		
    </div>

</body>

</html>