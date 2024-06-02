<?php
include 'db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT username, password FROM adminlogin WHERE BINARY username = :username AND BINARY password = :password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    $query1 = "SELECT username, password FROM userinfo WHERE BINARY username = :username AND BINARY password = :password";
    $stmt1 = $conn->prepare($query1);
    $stmt1->bindParam(':username', $username);
    $stmt1->bindParam(':password', $password);
    $stmt1->execute();

    if ($stmt->rowCount() > 0) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: Admin_Dashboard.php");
    }else if ($stmt1->rowCount() > 0) {
        $_SESSION['user_logged_in'] = true;
        $_SESSION['username'] = $username;
        header("Location: Home.php");
    } else {
        header("Location: Index.php?error=Incorrect username or password");
        exit();
    }
    exit();
}
