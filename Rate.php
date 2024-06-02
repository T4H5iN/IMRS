<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'], $_SESSION['username'], $_POST['rating'])) {
        $title_id = $_POST['id'];
        $username = $_SESSION['username'];
        $rating = $_POST['rating'];

        try {
            $sql = "INSERT INTO userRating (titleid, username, rating) VALUES (:id, :username, :rating)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $title_id, PDO::PARAM_STR);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                header("Location: " . $_SERVER['HTTP_REFERER'] . "&errorRat=Rating Added");
                exit();
            }
        } catch (PDOException $e) {
            header("Location: " . $_SERVER['HTTP_REFERER'] . "&errorRa=Already Rated");
            exit();
        }
    }
} else {
    echo "Invalid request method.";
}