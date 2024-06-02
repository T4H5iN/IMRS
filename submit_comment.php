<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['comment'])) {
        $comment = $_POST['comment'];
        $username = $_SESSION['username'];
        $publishDate = date('Y-m-d H:i:s');
        $titleId = $_GET['titleID'];

        $sql = "INSERT INTO Review (titleID, username, comment, publishDate) VALUES (:titleId, :username, :comment, CURRENT_TIMESTAMP())";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':titleId', $titleId);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':comment', $comment);

        $stmt->execute();

        header("Location: Info.php?id=" . $titleId);
    }
}