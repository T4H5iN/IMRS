<?php
session_start();
include 'db_connect.php';

$username = $_SESSION['username'];

if (isset($_POST['old_password'], $_POST['new_password'], $_POST['confirm_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Password validation checks
    if (strlen($new_password) < 8) {
        header("Location: Profile_Settings.php?errorPR=Password must be at least 8 characters");
        exit();
    }

    if (!preg_match('/[0-9]/', $new_password)) {
        header("Location: Profile_Settings.php?errorPR=Password must contain at least one number");
        exit();
    }

    if (!preg_match("/[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]/", $new_password)) {
        header("Location: Profile_Settings.php?errorPR=Password must contain at least one special character");
        exit();
    }

    if (!preg_match('/[a-z]/', $new_password)) {
        header("Location: Profile_Settings.php?errorPR=Password must contain at least one lowercase letter");
        exit();
    }

    if (!preg_match('/[A-Z]/', $new_password)) {
        header("Location: Profile_Settings.php?errorPR=Password must contain at least one uppercase letter");
        exit();
    }

    if ($new_password != $confirm_password) {
        header("Location: Profile_Settings.php?errorPR=Password does not match");
        exit();
    }

    $stmt = $conn->prepare("SELECT password FROM userinfo WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $db_password = $stmt->fetchColumn();

    if ($old_password === $db_password) {
        $stmt = $conn->prepare("UPDATE userinfo SET password = :new_password WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':new_password', $new_password);
        $stmt->execute();

        header("Location: Profile_Settings.php?yesPR=Password updated successfully");
        exit();
    } else {
        header("Location: Profile_Settings.php?errorPR=Old password is incorrect");
        exit();
    }
}

if (isset($_POST['new_email'], $_POST['confirm_email'])) {
    $new_email = $_POST['new_email'];
    $confirm_email = $_POST['confirm_email'];

    if ($new_email === $confirm_email) {
        $stmt = $conn->prepare("UPDATE userinfo SET mail = :new_email WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':new_email', $new_email);
        $stmt->execute();

        header("Location: Profile_Settings.php?yesMR=Email updated successfully");
        exit();
    } else {
        header("Location: Profile_Settings.php?errorMR=Email does not match");
        exit();
    }
}