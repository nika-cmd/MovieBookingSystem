<?php
session_start();
?>

<html>

<header>
	
	<link rel="stylesheet" type="text/css" href="customerAccountInfoF_styles.css">

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
	
    <h1>Account Information</h1>
	
	<!-- Display the account information of this user -->
    <div id ="info">
		
		<!-- Use PHP to get the account information of this user -->
		<?php
			
			include_once '../Database/connection.php';
			
			$con = getConnection();
			
			//Get all customer information
			$stmt = $con->prepare('CALL getCustomerInfo(?, ?)');
			
			//Get the email and password of this customer
			$email = $_SESSION["customerE-mail"];
			$password = $_SESSION["customerPassword"];

			$stmt-> bind_param('ss', $email, $password);  

			$stmt->execute();

			$result = $stmt->get_result();
				
			//Set the customer ID
			while($row = mysqli_fetch_array($result)){
				$firstName = $row['FirstName'];
				$lastName = $row['LastName'];
			}
			
			//Display the first name, last name, and email of this customer
			echo "<ul class='info'>";
			
			echo "<li>First Name: ". $firstName . "</li>";
			echo "<li>Last Name: ". $lastName . "</li>";
			echo "<li>E-mail: ". $_SESSION["customerE-mail"]. "</li>";
			
			echo "</ul>";
		
		?>
	</div>
	
	<!-- Display all bookings of a user using a table -->
	<div id = "booking">
		
		<!-- Create a table to display all bookings -->
		<table>
			<!-- Column Name -->
			<tr>
				<th>Date & Time</th>
				<th>Name</th>
				<th>Genre</th>
				<th>Duration</th>
				<th>Cost</th>
				<th>Seats Booked</th>
				<th>Food Ordered</th>
				<th>Room #</th>
				<th>Location</th>
				<th>Cancel</th>
				<br>
			</tr>
			
			<!-- Use PHP to get table data -->
			<?php
				include_once '../Database/connection.php';
			
				$con = getConnection();
				
				//Get the email and password of the customer
				$email = $_SESSION["customerE-mail"];
				$password = $_SESSION["customerPassword"];
				
				//Get the customer ID of the customer
				$stmt = $con->prepare('CALL checkCustomerAccount(?, ?)');

				$stmt-> bind_param('ss', $email, $password);  

				$stmt->execute();

				$result = $stmt->get_result();
				
				//Set the customer ID
				while($row = mysqli_fetch_array($result)){
					$customerID = $row['CustomerID'];
				}
				
				$con->next_result();
				
				//Get the information of the movie showings that the customer has booked (Movie name, genre, duration, Date and Time, the cost of the booking, and location)
				$stmt2 = $con->prepare('CALL getCustomerBookings(?, ?)');
				
				$stmt2-> bind_param('ss', date('Y-m-d'), $customerID);
				
				$stmt2->execute();
				
				$result2 = $stmt2->get_result();
				
				$con->next_result();
				
				//For every movie showing booked by this customer...
				while($row2 = mysqli_fetch_array($result2)){
					//Get all seats booked for this movie showing by this customer
					$stmt3 = $con->prepare('CALL getCustomerSeat(?, ?, ?)');
					
					$stmt3-> bind_param('sss', $row2['Room_No'], $customerID, $row2['DateTime']);
				
					$stmt3->execute();
				
					$result3 = $stmt3->get_result();
					
					$con->next_result();
					
					//Get all food ordered for this movie showing by this customer
					$stmt4 = $con->prepare('CALL getCustomerFood(?, ?, ?)');
						
					$stmt4-> bind_param('sss', $customerID, $row2['Room_No'], $row2['DateTime']);
				
					$stmt4->execute();
				
					$result4 = $stmt4->get_result();
					
					$con->next_result();
					
					//Display the booking information
					echo "
					<tr>
						<td>". $row2['DateTime']. "</td>
						<td>". $row2['Name']. "</td>
						<td>". $row2['Genre']. "</td>
						<td>". $row2['Duration']. "m". "</td>
						<td> $". $row2['Cost']. "</td>
						<td>"; 
						
						//For every seat booked by this user for this movie showing...
						while($row3 = mysqli_fetch_array($result3)){
							//Display all seats that this user has booked for this movie showing
							echo $row3['SeatID']. ": ". $row3['Seat_Type']. "<br>";
						}
						
						
						echo "</td>
						<td>";
						
						$i = 0;
						
						//For every food ordered by this customer for this movie showing... 
						while($row4 = mysqli_fetch_array($result4)){
							
							//Get the information of the food ordered for this movie showing
							$stmt5 = $con->prepare('CALL getFoodByID(?)');
							
							$stmt5-> bind_param('s', $row4['FoodID']);
							
							$stmt5->execute();
				
							$result5 = $stmt5->get_result();
							
							$con->next_result();
							
							//For every food ordered...
							while($row5 = mysqli_fetch_array($result5)){
								//Display the type of food, the description of the food, and the amount the customer has ordered
								if($i != 0){
								echo "<br>";
								}
								if($row5['Popcorn'] == 1){
									echo $row5['Description']. " Popcorn (". $row5['Size']. ") Amount: ". $row4['Quantity'];
								}
								else if($row5['Drink'] == 1){
									echo $row5['Description']. " (". $row5['Size']. ") Amount: ". $row4['Quantity'];
								}
								else if($row5['Candy'] == 1){
									echo $row5['Description']. " (". $row5['Size']. ") Amount: ". $row4['Quantity'];
								}
								else if($row5['Poutine'] == 1){
									echo $row5['Description']. " Poutine (". $row5['Size']. ") Amount: ". $row4['Quantity'];
								}
								else if($row5['Nacho'] == 1){
									echo $row5['Description']. " Nachos (". $row5['Size']. ") Amount: ". $row4['Quantity'];
								}
								$i = $i + 1;
							}
							
							
						}
						
						//If there is food ordered, then offer the option to cancel it
						if($result4->num_rows > 0){
							echo "<form action='cancelFoodB.php' method='post'>
									<input class='b' type='submit' value='Cancel'>
									<input class='b' type='hidden' name='RoomNo' value='". $row2['Room_No'] ."'>
									<input class='b' type='hidden' name='DateTime' value='". $row2['DateTime'] ."'>
								</form>";
						}
						
						//Create a form for every movie showing to allow the customer to cancel the movie showing
						echo "</td>
						<td>". $row2['Room_No']. "</td>
						<td>". $row2['Location']. "</td>
						<td>
							<form action='cancelMovieB.php' method='post'>
								<input class='b' type='submit' value='Cancel'>
								<input class='b' type='hidden' name='RoomNo' value='". $row2['Room_No'] ."'>
								<input class='b' type='hidden' name='DateTime' value='". $row2['DateTime'] ."'>
							</form>
						</td>
					</tr>
					<br>";
				}
				
			?>
		</table>
		
    </div>

</body>

</html>