<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imdbId = $_POST['id'];
    $listName = $_POST['list'];
    $username = $_SESSION['username'];

    $sql = "INSERT INTO List (username, titleID, listName) VALUES (:username, :titleID, :listName)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':titleID', $imdbId);
    $stmt->bindParam(':listName', $listName);

    $stmt->execute();

    header("Location: " . $_SERVER['HTTP_REFERER'] . "&errorAdd=Added to List");
} else {
    header("Location: " . $_SERVER['HTTP_REFERER'] . "&errorAd=Already Added to List");
}
