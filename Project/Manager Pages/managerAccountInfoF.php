<?php
session_start();
?>

<html>

<header>
	
	<link rel="stylesheet" type="text/css" href="managerAccountInfoF_styles.css">

	<title></title>

</header>

<body>
    <nav class ="navbar" id ="navbar">
        <p1> Website </p1>
        <ul>
            <li> <a href="managerAccountInfoF.php"> Account Info </a> </li>
            <li> <a href="editMovieF.php"> Edit Movies </a> </li>
            <li> <a href="editMovieShowingF.php"> Edit Movie Showings </a> </li>
    
        </ul>
    </nav>
	<h1>Manager Account</h1>
	<div>
        <?php
			include_once '../Database/connection.php';

			// Create connection
    		$con = getConnection();
			
			//Get all manager information
			$stmt = $con->prepare('SELECT * FROM Employee WHERE Email_Address = ? AND Password = ?');
			
			//Get the email and password of this manager
			$email = $_SESSION["employeeE-mail"];
			$password = $_SESSION["employeePassword"];

			$stmt-> bind_param('ss', $email, $password);  

			$stmt->execute();

			$result = $stmt->get_result();
			
			$value = $result->fetch_object();
			
			//Display the manager's information
			echo "<ul class='info'>";
			
			echo "<li>First Name: ". $value->First_Name . "</li>";
			echo "<li>Last Name: ". $value->Last_Name . "</li>";
			echo "<li>E-mail: ". $_SESSION["employeeE-mail"]. "</li>";
			echo "<li>Birthdate: ". $value->DOB . "</li>";
            echo "<li>Address: ". $value->Address . "</li>";

			$_SESSION["managerTheatreID"] = $value->TheatreID;
            
            // getting the theatre information
            $stmt = $con->prepare('CALL getTheatreLocation (?)');
			$stmt-> bind_param('i', $value->TheatreID);  

			$stmt->execute();

			$result = $stmt->get_result();
			
			$value = $result->fetch_object();
            echo "<li>Theatre Location: ". $value->Location . "</li>";

			$_SESSION["managerLocation"] = $value->Location;

            echo "</ul>";
		
		?>
	</div>
</body>
</html>




