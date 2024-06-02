<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
session_start();
include 'db_connect.php';

$id = $_GET['id'];

$conn->beginTransaction();

try {
    // Delete from the genres table
    $stmt = $conn->prepare("DELETE FROM genres WHERE id = :id");
    $stmt->execute(['id' => $id]);

    // Delete from the directors table
    $stmt = $conn->prepare("DELETE FROM directors WHERE id = :id");
    $stmt->execute(['id' => $id]);

    // Delete from the writers table
    $stmt = $conn->prepare("DELETE FROM writers WHERE id = :id");
    $stmt->execute(['id' => $id]);

    // Delete from the actors table
    $stmt = $conn->prepare("DELETE FROM actors WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $stmt = $conn->prepare("DELETE FROM episodes WHERE parentId = :id");
    $stmt->execute(['id' => $id]);

    $stmt = $conn->prepare("DELETE FROM titles WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $conn->commit();

    header("Location: Manage_Entries.php?sux=Title deleted successfully!");
} catch (Exception $e) {
    $conn->rollBack();
    header("Location: Manage_Entries.php?suxx=" . urlencode($e->getMessage()));
    exit();
}
?>
