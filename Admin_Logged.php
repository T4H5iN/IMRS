<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: Index.php");
    exit();
}
