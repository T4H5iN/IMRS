<?php
    include 'db_connect.php';

    try {

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            //$name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password1'];

            if (strlen($password) < 8) {
                header("Location: Index.php?errorPR=Password must be at least 8 characters");
                exit();
            }

            if (!preg_match('/[0-9]/', $password)) {
                header("Location: Index.php?errorPR=Password must contain at least one number");
                exit();
            }

            if (!preg_match("/[!@#$%^&*()_+\-=\[\]{};':\"\\|,.<>\/?]/", $password)) {
                header("Location: Index.php?errorPR=Password must contain at least one special character");
                exit();
            }

            if (!preg_match('/[a-z]/', $password)) {
                header("Location: Index.php?errorPR=Password must contain at least one lowercase letter");
                exit();
            }

            if (!preg_match('/[A-Z]/', $password)) {
                header("Location: Index.php?errorPR=Password must contain at least one uppercase letter");
                exit();
            }
            
            $confirmPassword = $_POST['confirm'];
            $gender = $_POST['gender'];
            $dob = $_POST['dob'];
            $sq = $_POST['sq'];

            $stmt = $conn->prepare("SELECT * FROM userinfo WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                header("Location: Index.php?errorU=Username already exists");
                exit();
            }

            if ($password != $confirmPassword) {
                header("Location: Index.php?errorP=Password does not match");
                exit();
            }

            $f_dob = date('Y-m-d', strtotime($dob));
            $query = "INSERT INTO userinfo (username, password, mail, Gender, dateofBirth, securityQ, regDate) VALUES (:username, :password, :email, :gender, :f_dob, :sq, CURRENT_TIMESTAMP())";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':f_dob', $f_dob);
            $stmt->bindParam(':sq', $sq);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                header("Location: Index.php?success=Registration successful");
                exit();
            }
        }

        $conn = null;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }