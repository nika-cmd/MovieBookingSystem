<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

include_once '../Database/connection.php';

$con = getConnection();

// if $_GET['TheatreID'] is declared get it or end
$theatreID = isset($_GET['TheatreID']) ? $_GET['TheatreID'] : die();


// get all showings for that manager's theatre
$stmt = $con->prepare('CALL getAllShowings(?)');  
$stmt->execute([$theatreID]);

//Get movie Showings
if ($stmt->rowCount() > 0) {
    
    $movieshowings = array();
    $movieshowings["movie_showings"] = array();

    // movies showings for theatre from database
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        extract ($row);
        $ms = array(
            "Name" => $Name,
            "RoomNo" => $RoomNo,
            "DateTime" => $DateTime
        );

        array_push($movieshowings["movie_showings"], $ms);
    }
    echo json_encode($movieshowings);
}
else {
    echo "No movies showings are in the database";
}


?>