<?php
session_start();
include 'db_connect.php';

$username = $_GET['id'];
try {
    $stmt = $conn->prepare("DELETE FROM userinfo WHERE username = :id");
    $stmt->execute(['id' => $username]);


    if($stmt->rowCount() == 0) {
        header("Location: Manage_Users.php?suxx=Unable to perform such action!");
    }else{
        header("Location: Manage_Users.php?sux=User deleted successfully!");

    }
    exit();
}catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

