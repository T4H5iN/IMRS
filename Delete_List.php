<?php
session_start();
include 'db_connect.php';
$username = $_SESSION['username'];
$id = $_GET['id'];

$conn->beginTransaction();

try {
    $stmt = $conn->prepare("DELETE FROM list WHERE username = :username and listName = :id");
    $stmt->execute(['username' => $username, 'id' => $id]);

    $stmt = $conn->prepare("DELETE FROM list_name WHERE username = :username and listName = :id");
    $stmt->execute(['username' => $username, 'id' => $id]);

    $conn->commit();

    header("Location: List.php");
} catch (Exception $e) {
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
    exit();
}