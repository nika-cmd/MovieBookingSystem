
<?php
session_start();
?>

<html>

<header>
	
	<link rel="stylesheet" type="text/css" href="editMovieShowingF_styles.css">

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

	<h1>Add a New Movie Showing</h1>
	<div id = "add new showing">

		<?php
			include_once '../Database/connection.php';

			echo "<body> Adds showings to " . $_SESSION['managerLocation']. 
			" Location (TheatreID is " .$_SESSION['managerTheatreID']. ")</body>";
	
			echo "<form action='addShowingB.php' method='post'>";

			// Create connection
			$con = getConnection();

			// getting movies that can be select for a movie showing
			$result = $con->query('SELECT * FROM Movie');
			
			echo "<br><label for='movienameID'> Movie: </label>
				<select name='movienameID' id='movienameID'> ";
			while ($row = $result->fetch_assoc()) {
				echo "<option value='" .$row['MovieID'] . "'>" . $row['Name'] . "</option>";
			}

			echo "</select>";

			// getting the rooms of the manager's theatre
			$stmt = $con->prepare('CALL getTheatreRooms(?)');
			$stmt-> bind_param('i', $_SESSION['managerTheatreID']);  
			$stmt->execute();

			$result = $stmt->get_result();
			echo "<label for='room'>&emsp; Room_No: </label>
				<select name='roomNo' id='roomNo'> ";
			while ($row = $result->fetch_assoc()) {
				echo "<option value='" .$row['Room_No'] . "'>" . $row['Room_No'] . "</option>";
			}

			echo "</select>";
        
			// setting a date for movie showing
			echo "<label for='moviedate'>&emsp; Date: </label>
				<input type='date' id='moviedate' name='moviedate'
					min='" . date('Y-m-d') .  "' required>";


			// setting a time for the movie showing
			echo "<label for='movietime'>&emsp; Time: </label>
				<input type='time' id='movietime' name='movietime' required>";

			// add movie showing button
			echo"&emsp; <input class = 'b' type='submit' value='add'>";

        	echo "</form>";
		?>
    </div>

	<h1>Current Movie Showings (<?php echo $_SESSION["managerLocation"]; ?> Location)</h1>
	<body>
		*** Note removing a movie showing from the database will remove all bookings, orders and seats for that movie ***
	</body>
	<div id = "current movie showings for theatre">
			<!-- table of movie showings currently in database -->
		<table>
			<!-- Column Name -->
			<tr>
				<th>Movie</th>
				<th>Room_No</th>
				<th>Date/Time</th>
				<th>Remove</th>
				<br>
			</tr>

        	<?php

				// Create connection
				$currShowingCon = getConnection();
			
				// get all showings for that manager's theatre
				$stmt = $currShowingCon->prepare('CALL getAllShowings(?)');
				$stmt-> bind_param('i', $_SESSION['managerTheatreID']);  
				$stmt->execute();
				$result = $stmt->get_result();
				
				//Get the movie name, genre, and duration and display
				if ($result->num_rows > 0) {

					// movies from database
					while ($row = $result->fetch_assoc()) {
						echo "<tr><td>"
							.$row['Name']. "</td><td>"
							.$row['RoomNo']. "</td><td>"
							.$row['DateTime']. "</td><td>
								<form action='removeShowingB.php' method='post'>
									<input class='b' type='submit' value='remove'>
									<input class='b' type='hidden' name='showingDTRemove' value='". $row['DateTime'] ."'>
									<input class='b' type='hidden' name='RoomNoRemove' value='". $row['RoomNo'] ."'>
								</form></td>";
					}
				}
				else {
					echo "No movies showings are in the database";
				}
            
        	?>
		</table>
	</div>
	
<body>
</html>





