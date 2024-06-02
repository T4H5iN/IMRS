<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'db_connect.php';

$username = $_GET['u'];
$id=$_GET['id'];

try {
    $stmt = $conn->prepare("DELETE FROM Review WHERE username = :u AND titleID = :id");
    $stmt->execute(['u' => $username, 'id' => $id]);

    if ($stmt->rowCount() == 0) {
        header("Location: Manage_reviews.php?suxx=Unable to perform such action!");
    } else {
        header("Location: Manage_reviews.php?sux=Comment Removed");
    }
    exit();
}catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>