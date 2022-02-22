<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../Database/connection.php';

$con = getConnection();

$data = json_decode(file_get_contents("php://input"));

$datetime = $data->DateTime;
$room = $data->RoomNo;
$movieid = $data->MovieID;
$duration;
    
// getting the duration of movie
$result = $con->query ('SELECT Duration FROM movie WHERE MovieID =' . $movieid);
    
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $duration = $row["Duration"];
    }
}

// finding the endtime of movie (to be) added
$endtime = date('Y-m-d H:i:s', strtotime('+'.$duration.' minutes', strtotime($datetime)));
    

function add_showing ($dt, $rm, $mid) {
   
    // add movie showing
    $c = getConnection();
    //$stmt = $c->prepare('INSERT INTO movie_showing VALUES (?, ?, ?)');
    $stmt = $c->prepare('CALL addMovieShowing (?, ?, ?)');
    $stmt->execute([$dt, $rm, $mid]);

    // add seats for that movie showing
    $stmt = $c->prepare('CALL addSeatsForShowing(?, ?)');
    $stmt->execute([$dt, $rm]);

    return;
}

$stmt = $con->prepare('CALL getShowingsInRoom(?)');
$stmt->execute([$room]);

$conflict = 0; // is 1 when any time conflicts are flaged

// if movie is booked for the same room check for conflict
if ($stmt->rowCount() > 0) {

    // checking if the date/time conflicts
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $other_datetime = $row["DateTime"];
        $other_endtime =date('Y-m-d H:i:s', strtotime('+'. $row['Duration'].' minutes', strtotime($other_datetime)));
        
        // if there is overlap
        if ($datetime <= $other_endtime && $endtime >= $other_datetime) {
            
            $conflict = 1;
            break;
        }
    
        
    }
} 

// if no conflicts found
if ($conflict == 0) {
    add_showing($datetime, $room, $movieid);
    echo "Showing for movieID = " . $movieid . " in room " . $room . " on " . $datetime 
        . " has been added to the database, and corresponding seats have been added";
}
else {
    echo "This showing conflicts with other exiting showings, and therefore was NOT added to the Database.";
}

?>