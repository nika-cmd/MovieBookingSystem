<?php
session_start();
include_once '../Database/connection.php';

    $movieDT = $_POST["showingDTRemove"];
    $room = $_POST["RoomNoRemove"];
    
    echo $movieDT . "<br>";
    echo $room  . "<br>";
    
   // Create connection
   $con = getConnection();
    
    // Delete specified movie showing
    $stmt = $con->prepare('DELETE FROM movie_showing WHERE DateTime = ? AND RoomNo = ?');

    $stmt-> bind_param('si', $movieDT, $room);  
    
    $stmt->execute();
    header("Location: ../Manager Pages/editMovieShowingF.php");
?>