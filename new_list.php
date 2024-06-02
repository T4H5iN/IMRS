<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['username']) && isset($_POST['new_list'])) {
    $username = $_SESSION['username'];
    $list_name = $_POST['new_list'];

    try {
        $sql = "INSERT INTO List_name (username, listName) VALUES (:username, :new_list)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':new_list', $list_name, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: List.php?errorRR=List added");
            exit();
        }
    } catch (PDOException $e) {
        header("Location: List.php?errorLR=List already exists");
        exit();
    }
} else {
    echo "Invalid request.";
}

