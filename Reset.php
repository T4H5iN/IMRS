<?php
include 'db_connect.php';
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $newPassword = $_POST['password1'];
        $confirmPassword = $_POST['confirm'];
        $securityQuestionAnswer = $_POST['sq'];

        // Check if new password and confirm password match
        if ($newPassword !== $confirmPassword) {
            header("Location: Password_Recovery.php?errorP=Passwords do not match");
            exit();
        }

        // Prepare SQL statement to fetch user's security question answer from the database
        $stmt = $conn->prepare("SELECT securityQ FROM userinfo WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists and the security question answer matches
        if ($result && $result['securityQ'] === $securityQuestionAnswer) {
            // Prepare SQL statement to update the user's password
            $stmt = $conn->prepare("UPDATE userinfo SET password = :password WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $newPassword);
            $stmt->execute();

            header("Location: Index.php?success=Password reset successful");
        } else {
            header("Location: Password_Recovery.php?errorPR=Invalid username or security question answer");
        }
        exit();
    }
}catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}