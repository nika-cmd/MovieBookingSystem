<?php
session_start();
?>
<html>

<header>
	
	<link rel="stylesheet" type="text/css" href="newEmployeeAccountF_styles.css">

	<title>Create Account</title>

</header>

<body>
	<h1>Create an Account</h1>
	<div>
		<form action="addEmployeeAccountB.php" method="post">
			SSN: <input type="text" name="SSN"><br>
			First Name: <input type="text" name="Firstname"><br>
			Last Name: <input type="text" name="Lastname"><br>
			Date of Birth (xxxx-xx-xx, year-month-day): <input type="text" name="DateofBirth"><br>
			Address: <input type="text" name="Address"><br>
			E-mail: <input type="text" name="E-mail"><br>
			Password: <input type="password" name="Password"><br>
			Work Location: <input type="text" name="WorkLocation"><br>
			<input class = "b" type="submit" value="Login">
		</form>
	</div>

</body>
</html>