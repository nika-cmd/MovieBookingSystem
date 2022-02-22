<?php
session_start();
?>

<html>

<header>
	
	<link rel="stylesheet" type="text/css" href="customerFoodF_styles.css">

	<title></title>

</header>

<body>

	<!-- Navigation Bar -->
	<nav class ="navbar" id = "navbar">
		<p1> Website </p1>
		<ul>

			<li> <a href="customerHomeF.php"> Movies </a> </li>
			<li> <a href="customerFoodF.php"> Food </a> </li>
			<li> <a href="customerAccountInfoF.php"> Account Information</a> </li>
		</ul>
	</nav>
	
	<!-- Get all Food Options for each booked Movie Showing -->
    <h1>Food Options</h1>
    <div id ="food">
		
		<!-- Create a table to display the food options for each booked movie showing -->
		<table>
		
			<!-- Column Names -->
			<tr>
				<th>Date & Time</th>
				<th>Name</th>
				<th>Room #</th>
				<th>Food</th>
			</tr>
			
			<!-- Get all the food options for the movie showings that the customer has booked -->
			<?php
				include_once '../Database/connection.php';
				
				$con = getConnection();
				
				//Get the customer email and password using Session
				$email = $_SESSION["customerE-mail"];
				$password = $_SESSION["customerPassword"];
				
				//Get the customer ID of this customer
				$stmt = $con->prepare('CALL checkCustomerAccount(?, ?)');

				$stmt-> bind_param('ss', $email, $password);  

				$stmt->execute();

				$result = $stmt->get_result();
				
				//Set the customer ID
				while($row = mysqli_fetch_array($result)){
					$customerID = $row['CustomerID'];
				}
				
				$con->next_result();
				
				//Get the name of the movie, the date and time of the movie, and the room # of the movie showing the customer has booked seats for
				$stmt2 = $con->prepare('CALL getMovieShowingFood(?, ?)');
				
				$stmt2-> bind_param('ss', date('Y-m-d'), $customerID);
				
				$stmt2->execute();
				
				$result2 = $stmt2->get_result();
				
				$con->next_result();
				
				//For each movie showing that the user has booked seats for...
				while($row2 = mysqli_fetch_array($result2)){
					
					//Add data to the table
					echo" <tr>
					<td> ". $row2['DateTime']. "</td>
					<td> ". $row2['Name']. "</td>
					<td> ". $row2['Room_No']. "</td>
					<td>
					";
					
					//Get all food options
					$stmt3 = $con->prepare('CALL getAllFood()');
				
					$stmt3->execute();
				
					$result3 = $stmt3->get_result();
					
					$con->next_result();
					
					$i = 0;
					
					//For each food option...
					while($row3 = mysqli_fetch_array($result3)){
						//Display the type of food, its description, its size, and its price
						if($i != 0){
							echo "<br>";
						}
						if($row3['Popcorn'] == 1){
							echo $row3['Description']. " Popcorn (". $row3['Size']. ") $". $row3['Price'];
						}
						else if($row3['Drink'] == 1){
							echo $row3['Description']. " (". $row3['Size']. ") $". $row3['Price'];
						}
						else if($row3['Candy'] == 1){
							echo $row3['Description']. " (". $row3['Size']. ") $". $row3['Price'];
						}
						else if($row3['Poutine'] == 1){
							echo $row3['Description']. " Poutine (". $row3['Size']. ") $". $row3['Price'];
						}
						else if($row3['Nacho'] == 1){
							echo $row3['Description']. " Nachos (". $row3['Size']. ") $". $row3['Price'];
						}
						//Create a form for each food option (a drop down for the quantity that the user wants to buy and a submit button)
						echo ":
							<form action='buyFoodB.php' method='post'>
								<select name='quantity'>
									<option value='0'>0</option>
									<option value='1'>1</option>
									<option value='2'>2</option>
									<option value='3'>3</option>
									<option value='4'>4</option>
									<option value='5'>5</option>
								</select>
								<input class='b' type='submit' value='Buy'>
								<input class='b' type='hidden' name='FoodID' value='". $row3['FoodID'] ."'>
								<input class='b' type='hidden' name='DateTime' value='". $row2['DateTime'] ."'>
								<input class='b' type='hidden' name='Room_No' value='". $row2['Room_No'] ."'>
							</form>
						";
						$i = $i + 1;
					}
					
					echo "</td>
					</tr>";
					
					
				}
				
				
				
			?>
		</table>
		
	</div>
	

</body>

</html>