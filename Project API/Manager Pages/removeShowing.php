<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

include_once '../Database/connection.php';

$con = getConnection();

$data = json_decode(file_get_contents("php://input"));

$dt = $data->DateTime;
$room = $data->RoomNo;

try {

    //$stmt = $con->prepare('CALL removeMovieShowing (?, ?)'); // for some reason prepared statement is not deleting showing
    $stmt = $con->prepare ('DELETE FROM movie_showing WHERE DateTime = ? AND RoomNo = ?');
	$stmt->execute([$dt, $room]);
    echo "Movie Showing in room = " . $room . " on " . $dt . " removed from database.";


} catch(\PDOException $e){
    echo "Error: could not delete Movie Showing.";
    exit();
}

?>