<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");

include_once '../Database/connection.php';

$con = getConnection();

$data = json_decode(file_get_contents("php://input"));


$id = $data->MovieID;

try {

    $stmt = $con->prepare('CALL removeMovie (?)');
	$stmt->execute([$id]);
    echo "Movie, with id = " . $id . " removed from database.";


} catch(\PDOException $e){
    echo "Error: could not delete Movie.";
    exit();
}

?>