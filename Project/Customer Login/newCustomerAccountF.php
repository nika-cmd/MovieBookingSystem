<?php
session_start();
?>

<html>
<header>
	
	<link rel="stylesheet" type="text/css" href="newCustomerAccountF_styles.css">

	<title>Create Account</title>

</header>

<body>
	<h1>Create an Account</h1>
	<div>
		<form action="addCustomerAccountB.php" method="post">
			First Name: <input type="text" name="Firstname"><br>
			Last Name: <input type="text" name="Lastname"><br>
			E-mail: <input type="text" name="E-mail"><br>
			Password: <input type="password" name="Password"><br>
			<input class = "b" type="submit" value="Login">
		</form>
	</div>
</body>

</html>