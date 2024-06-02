<?php
session_start();
include 'db_connect.php'; // Include your database connection if needed

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if file was uploaded without errors
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $username = $_SESSION['username']; // Get the username from the session

        // Define the directory to save the uploaded file
        $uploadDir = 'Profile_Image/';

        // Ensure the upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Get file info
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $fileName = $_FILES['profile_picture']['name'];
        $fileSize = $_FILES['profile_picture']['size'];
        $fileType = $_FILES['profile_picture']['type'];
        $fileNameCmps = explode('.', $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Define allowed file types
        $allowedFileExtensions = array('jpg', 'jpeg', 'png', 'gif');

        // Check if the file has an allowed extension
        if (in_array($fileExtension, $allowedFileExtensions)) {
            // Create a unique file name
            $newFileName = $username . '.' . $fileExtension;

            // Path to save the uploaded file
            $destPath = $uploadDir . $newFileName;

            // Move the file to the target directory
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                echo "Profile picture uploaded successfully.";
            } else {
                echo "There was an error moving the uploaded file.";
            }
        } else {
            echo "Upload failed. Allowed file types: " . implode(',', $allowedFileExtensions);
        }
    } else {
        echo "There was an error uploading the file.";
    }
}