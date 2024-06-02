<?php
session_start();
include 'db_connect.php';
$username = $_SESSION['username'];
$id = $_GET['id'];
$listName = $_GET['list'];

try {
    $stmt = $conn->prepare("DELETE FROM list WHERE username = :username and titleid = :id and listName = :list");
    $stmt->execute(['username' => $username, 'id' => $id, 'list' => $listName]);

    header("Location: " . $_SERVER['HTTP_REFERER']);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}