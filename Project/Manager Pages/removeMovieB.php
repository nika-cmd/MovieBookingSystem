<?php
session_start();
include_once '../Database/connection.php';

    $id = $_POST["MovieID"];

    // Create connection
    $con = getConnection();
    
    // Delete specified movie
    $stmt = $con->prepare('CALL removeMovie(?)');

    $stmt-> bind_param('i', $id);  

    $stmt->execute();
    header("Location: ../Manager Pages/editMovieF.php");
?>