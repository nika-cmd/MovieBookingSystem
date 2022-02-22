<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

include_once '../Database/connection.php';

$con = getConnection();

$data = json_decode(file_get_contents("php://input"));

$movie_name = $data->Name;
$genre = $data->Genre;
$duration = $data->Duration;

try {

    $stmt = $con->prepare('CALL addMovie (?, ?, ?)');
	$stmt->execute([$movie_name, $genre, $duration]);
    echo "Movie added to database.";


} catch(\PDOException $e){
    echo "Error: could not add movie.";
    exit();
}


?>