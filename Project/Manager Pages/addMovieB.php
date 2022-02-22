<?php
    session_start();
    include_once '../Database/connection.php';

    $name = $_POST["movie"];
    $genre = $_POST["genre"];
    $duration = $_POST["duration"];
    echo $name . "<br>";
    echo $genre . "<br>";
    echo $duration;

    // Create connection
    $con = getConnection();
    
    // Add new movie to database
    $stmt = $con->prepare('CALL addMovie(?, ?, ?)');

    $stmt-> bind_param('ssi', $name, $genre, $duration);  

    $stmt->execute();
    header("Location: ../Manager Pages/editMovieF.php");
?>