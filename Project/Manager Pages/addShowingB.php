<?php
    session_start();
    include_once '../Database/connection.php';

    // Create connection
    $con = getConnection();
    

    $movieid = $_POST["movienameID"];
    $room = $_POST["roomNo"];
    $date = $_POST["moviedate"];
    $stime = $_POST["movietime"] . ":00";
    $datetime = $date . " " . $stime;
    $duration;
    
    // getting the duration of movie
    $result = $con->query ('SELECT Duration FROM movie WHERE MovieID =' . $movieid);
    


    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $duration = $row["Duration"];
        }
    }

    // finding the endtime of movie (to be) added
    $endtime = date('Y-m-d H:i:s', strtotime('+'.$duration.' minutes', strtotime($datetime)));
    

    function add_showing ($dt, $rm, $mid) {
        echo "<br> add showing called";
        echo $dt . " " . $rm . " ". $mid;
        // add movie showing
        $c = getConnection();
        //$stmt = $c->prepare('INSERT INTO movie_showing VALUES (?, ?, ?)');
        $stmt = $c->prepare('CALL addMovieShowing (?, ?, ?)');
        $stmt-> bind_param('sii', $dt, $rm, $mid);  
        $stmt->execute();

        // add seats for that movie showing
        $stmt = $c->prepare('CALL addSeatsForShowing(?, ?)');
        $stmt->bind_param('si', $dt, $rm);
        $stmt->execute();

        return;
    }

    $stmt = $con->prepare('CALL getShowingsInRoom(?)');
    $stmt-> bind_param('i', $room);  
    $stmt->execute();
    $result = $stmt->get_result();

	$conflict = 0; // is 1 when any time conflicts are flaged
	
    // if movie is booked for the same room check for conflict
    if ($result->num_rows > 0) {

        // checking if the date/time conflicts
        while ($row = $result->fetch_assoc()) {
            $other_datetime = $row["DateTime"];
            $other_endtime =date('Y-m-d H:i:s', strtotime('+'. $row['Duration'].' minutes', strtotime($other_datetime)));
            echo "<br><br>" . $other_datetime;
            echo "<br>" . $other_endtime;
            // if 
            if ($datetime <= $other_endtime && $endtime >= $other_datetime) {
                echo "overlap";
                $conflict = 1;
                break;
            }
        
            
        }
    } 

    // if no conflicts found
    if ($conflict == 0) {
        add_showing($datetime, $room, $movieid);
    }

    header("Location: ../Manager Pages/editMovieShowingF.php");
?>