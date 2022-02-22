<?php
session_start();
?>
<html>

<header>
	
	<link rel="stylesheet" type="text/css" href="existingEmployeeAccountF_styles.css">

	<title></title>

</header>
<body>
	<h1>Welcome!</h1>
	<div>
		<form action="checkEmployeeAccountB.php" method="post">
		E-mail: <br><input type="text" name="E-mail"><br><br>
		Password: <br><input type="password" name="Password"><br><br>
			<input class="b" type="submit" value="Login">
			<p class="or" style="text-align: center;">OR</p>
			<button class="b" type="button" onclick="location.href = 'newEmployeeAccountF.php';"> Create Account </button> 

		</form>
	</div>
</body>
</html>