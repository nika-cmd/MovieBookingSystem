<?php
session_start();
?>

<html>

<header>
	
	<link rel="stylesheet" type="text/css" href="customerHomeF_styles.css">

	<title></title>

</header>

<body>
	
	<!-- Navigation Bar -->
	<nav class ="navbar" id = "navbar">
		<p1> Website </p1>
		<ul>

			<li> <a href="customerHomeF.php"> Movies </a> </li>
			<li> <a href="customerFoodF.php"> Food </a> </li>
			<li> <a href="customerAccountInfoF.php"> Account Information </a> </li>
		</ul>
	</nav>
	
	
	<h1>Available Movie Showings</h1>
	
	<div>
		<!-- Table of Movie Showings -->
		<table>
			
			<!-- Column Names -->
			<tr>
				<th>Name</th>
				<th>Genre</th>
				<th>Duration</th>
				<th>Date & Time</th>
				<th>Location</th>
				<th>Seats</th>
				<br>
			</tr>
			
			<!-- PHP Getting Movie Showings -->
			<?php
				include_once '../Database/connection.php';
				
				$con = getConnection();
				
				//Get Information of movie showings occurring in the future (Movie name, genre, duration, date and time, and location)
				$stmt = $con->prepare('CALL getAvailableMovieShowings(?)');
				
				$stmt-> bind_param('s', date('Y-m-d H:i:s'));
				
				$stmt->execute();
				
				$result = $stmt->get_result();
				
				$con->next_result();
				
				//For every result...
				while($row = mysqli_fetch_array($result)){
					
					//Find all Seats that are still available for the movie showing
					$stmt2 = $con->prepare('CALL getMovieShowingSeats(?, ?)');
					
					$stmt2-> bind_param('ss', $row['RoomNo'], $row['DateTime']);
				
					$stmt2->execute();
				
					$result2 = $stmt2->get_result();
					
					$con->next_result();
					
					//Output the information of the movie showing in the table
					echo "
						<tr>
							<td>". $row['Name']. "</td>
							<td>". $row['Genre']. "</td>
							<td>". $row['Duration']. "m". "</td>
							<td>". $row['DateTime']. "</td>
							<td>". $row['Location']. "</td>
							<td>";
								
								//If there are seats available...
								if($result2->num_rows > 0){
									//Create a multiple select form that allows user to select 1 or more seats
									echo "<form action='bookMovieB.php' method='post'>
											<select name='seats[]' multiple>";
									
									//Add every single available seat to the form
									while($row2 = mysqli_fetch_array($result2)){
										echo	"<option value='". $row2['SeatID'] ."'>". $row2['SeatID'] . ": ". $row2['Seat_Type']. "</option>";
									}
									
									//Record certain information useful for booking a seat for a movie showing
									echo "	</select>
											<input class='b' type='submit' value='Book'>
											<input class='b' type='hidden' name='RoomNo' value='". $row['RoomNo'] ."'>
											<input class='b' type='hidden' name='DateTime' value='". $row['DateTime'] ."'>
										 </form>";
								}
								
								//If there are no available seats...
								else{
									echo "Full";
								}
					echo "
							</td>
						</tr>
						<br>";
				}
				
			?>
		</table>
	</div>
	
	
</body>

</html>