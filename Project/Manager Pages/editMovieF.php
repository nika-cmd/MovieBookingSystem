
<?php
session_start();
?>

<html>

<header>
	
	<link rel="stylesheet" type="text/css" href="editMovieF_styles.css">

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
	<h1>Add New Movie</h1>
	<div id = "add movie">
		<form action="addMovieB.php" method="post">
			<label for="movie"> Movie Name: </label>
			<input type="text" name="movie" id="movie" required minlength="1" maxlength="25">

			<label for="genre"> &emsp;Genre: </label>
			<select name="genre" id="genre" required>
				<option value="Action">Action</option>
				<option value="Comedy">Comedy</option>
				<option value="Sci-Fi">Sci-Fi</option>
				<option value="Horror/Thriller">Horror/Thriller</option>
				<option value="Family">Family</option>
				<option value="Romance">Romance</option>
			</select> 

			<label for="duration"> &emsp;Duration(minutes): </label>
			<input type="number" name="duration" required min="1" max="300">
			
			&emsp;
			<input class = "b" type="submit" value="add">
		</form>
    </div>
	<h1>Movies in Database</h1>
	<body id ="note">
		*** Note removing a movie from the database will remove all showings, bookings, and orders for that movie ***
	</body>
    <div id = "current movies in database">
		<!-- table of movies currently in database -->
		<table>
			<!-- Column Name -->
			<tr>
				<th>Name</th>
				<th>Genre</th>
				<th>Duration (mins)</th>
				<th>Remove</th>
				<br>
			</tr>

        	<?php
				include_once '../Database/connection.php';
				
				// Create connection
				$con = getConnection();
			
				//Get all movies
				$result = $con->query('CALL getAllMovie()');
			
				//Get the movie name, genre, and duration and display
				if ($result->num_rows > 0) {

					// movies from database
					while ($row = $result->fetch_assoc()) {
						echo "<tr><td>"
							.$row["Name"]. "</td><td>"
							.$row["Genre"]. "</td><td>"
							.$row["Duration"]. "</td><td>
								<form action='removeMovieB.php' method='post'>
									<input class='b' type='submit' value='remove'>
									<input class='b' type='hidden' name='MovieID' value='". $row["MovieID"] ."'>
								</form></td>";
					}
				}
				else {
					echo "No movies are in the database";
				}
            
        	?>
		</table>
	</div>
</body>

</html>