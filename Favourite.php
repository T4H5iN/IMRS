<?php
session_start();
include 'db_connect.php';

if (isset($_GET['id']) && isset($_SESSION['username'])) {
    $title_id = $_GET['id'];
    $username = $_SESSION['username'];

    try {
        $sql = "INSERT INTO Favourite (titleid, username) VALUES (:id, :username)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $title_id, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&errorFav=Added to Favourite");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: " . $_SERVER['HTTP_REFERER'] . "&errorRa=Already Rated");
        exit();
    }
} else {
    echo "Invalid request.";
}

