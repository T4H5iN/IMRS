<?php
session_start();
include 'db_connect.php';

$username = $_GET['id'];
try {
    $stmt = $conn->prepare("DELETE FROM episodes WHERE episodeId = :id");
    $stmt->execute(['id' => $username]);


    if($stmt->rowCount() == 0) {
        header("Location: Manage_Episode.php?suxx=Unable to perform such action!");
    }else{
        header("Location: Manage_Episode.php?sux=Episode deleted successfully!");

    }
    exit();
}catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

