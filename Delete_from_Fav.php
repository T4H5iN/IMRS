<?php
session_start();
include 'db_connect.php';
$username = $_SESSION['username'];
$id = $_GET['id'];

try {
    $stmt = $conn->prepare("DELETE FROM Favourite WHERE username = :username and titleid = :id");
    $stmt->execute(['username' => $username, 'id' => $id]);

    header("Location: " . $_SERVER['HTTP_REFERER']);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}