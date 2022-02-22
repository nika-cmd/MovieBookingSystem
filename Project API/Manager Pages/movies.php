<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../Database/connection.php';

$con = getConnection();

$result = $con->query('CALL getAllMovie()');

if ($result->rowCount() > 0) {

    $movies = array();
    $movies["movies"] = array();

    // movies from database
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

        extract ($row);
        $m = array(
            "Name" => $Name,
            "Genre" => $Genre,
            "Duration" => $Duration
        );
        array_push($movies["movies"], $m);
    }
    echo json_encode($movies);
}
else {
    echo "No movies are in the database";
}

?>