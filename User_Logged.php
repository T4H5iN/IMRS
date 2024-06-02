<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: Index.php");
    exit();
}
