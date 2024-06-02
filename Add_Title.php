<?php
session_start();
include 'Admin_Logged.php';
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_title'])) {
    $id = $_POST['id'];
    $type = $_POST['type'];
    $primaryTitle = $_POST['primaryTitle'];
    $originalTitle = $_POST['originalTitle'];
    $startYear = $_POST['startYear'];
    $endYear = $_POST['endYear'];
    if (empty($endYear)) {
        $endYear = NULL;
    }
    $runtime = $_POST['runtime'];
    $plot = $_POST['plot'];
    $oscar = $_POST['oscar'];
    $genres = explode(', ', $_POST['genres']);
    $directors = explode(', ', $_POST['directors']);
    $writers = explode(', ', $_POST['writers']);
    $actors = explode(', ', $_POST['actors']);
    $rating = $_POST['rating'];
    $votes = $_POST['votes'];

    // File upload handling
    $targetDir = "Poster/";
    $targetFile = $targetDir . $id . ".jpg";
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["poster"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        unlink($targetFile); // Remove existing file
    }

    // Check file size (optional, example: limit to 5MB)
    if ($_FILES["poster"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg") {
        echo "Sorry, only JPG files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["poster"]["tmp_name"], $targetFile)) {
            // File successfully uploaded, proceed with database transaction
            $conn->beginTransaction();

            try {
                $stmt = $conn->prepare("INSERT INTO titles (id, type, primaryTitle, originalTitle, startYear, endYear, runtime, plot, oscar) VALUES (:id, :type, :primaryTitle, :originalTitle, :startYear, :endYear, :runtime, :plot, :oscar)");
                $stmt->execute([
                    'id' => $id,
                    'type' => $type,
                    'primaryTitle' => $primaryTitle,
                    'originalTitle' => $originalTitle,
                    'startYear' => $startYear,
                    'endYear' => $endYear,
                    'runtime' => $runtime,
                    'plot' => $plot,
                    'oscar' => $oscar
                ]);

                foreach ($genres as $genre) {
                    $stmt = $conn->prepare("INSERT INTO genres (id, genre) VALUES (:id, :genre)");
                    $stmt->execute(['id' => $id, 'genre' => $genre]);
                }

                foreach ($directors as $director) {
                    $stmt = $conn->prepare("INSERT INTO directors (id, director) VALUES (:id, :director)");
                    $stmt->execute(['id' => $id, 'director' => $director]);
                }

                foreach ($writers as $writer) {
                    $stmt = $conn->prepare("INSERT INTO writers (id, writer) VALUES (:id, :writer)");
                    $stmt->execute(['id' => $id, 'writer' => $writer]);
                }

                foreach ($actors as $actor) {
                    $stmt = $conn->prepare("INSERT INTO actors (id, actor) VALUES (:id, :actor)");
                    $stmt->execute(['id' => $id, 'actor' => $actor]);
                }

                $stmt = $conn->prepare("INSERT INTO rating (id, rating, noOfVotes) VALUES (:id, :rating, :votes)");
                $stmt->execute(['id' => $id, 'rating' => $rating, 'votes' => $votes]);

                $conn->commit();
                header("Location: Add_Title.php?sux=Title added successfully!");
                exit();
            } catch (Exception $e) {
                $conn->rollBack();
                echo "Failed: " . $e->getMessage();
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Style.css">
    <style>
        button {
            font-size: 15px;
            background-color: #123047;
            margin: auto;
            margin-right: 5px;
            padding: 0;
            width: 70px;
            height: 35px;
        }

        button:hover {
            background-color: #031020;
            color: white;
        }

        input {
            width: 300px;
        }
    </style>
    <title>Add Title - IMRS</title>
</head>
<body>
<div class="header">
    <a href="Home.php"><img src="Illustrations/imrs.png" alt="Logo" class="logo"></a>
    <div class="dropP">
        <?php
        $username = $_SESSION['username'];
        $imageFiles = glob("Profile_Image/{$username}.*");
        if (!empty($imageFiles)) {
            $imagePath = $imageFiles[0];
            echo "<a href='Profile.php'><img src='$imagePath' alt='User Logo' class='user-logo'></a>";
        } else {
            echo '<a href="Profile.php"><img src="Profile_Image/user.png" alt="User Logo" class="user-logo"></a>';
        }
        ?>
        <div class="dropP-tooltip">
            <form action="Logout.php" method="get">
                <button type="submit">Logout</button>
            </form>
        </div>
    </div>
</div>
<div style="display: flex">
    <div class="sidebar">
        <a style="margin-top: 50px" href="Admin_Dashboard.php">Admin Dashboard</a><br>
        <a href="Manage_Users.php">Manage Users</a><br>
        <a href="Add_Title.php" style="background-color: #031020">Add Entries</a><br>
        <a href="Add_Episode.php" >Add Episode</a><br>
        <a href="Manage_Entries.php">Modify Entries</a><br>
        <a href="Manage_Episode.php" >Modify Episode</a><br>
        <a href="Manage_reviews.php">Manage Reviews</a><br>
        <a href="Logout.php">Logout</a><br>
    </div>
    <div style="width: 100%">
        <h1>Add New Title</h1><hr>
        <br><div id="success" class="success">
            <?php
            if (isset($_GET['sux'])) {
                echo htmlspecialchars($_GET['sux']);
            }
            ?>
        </div>
        <div id="error" class="error-message">
            <?php
            if (isset($_GET['suxx'])) {
                echo htmlspecialchars($_GET['suxx']);
            }
            ?>
        </div><br>
        <form method="post" action="Add_Title.php" enctype="multipart/form-data">
            Upload Poster:&emsp;<input type="file" name="poster" accept=".jpg" required><br>
            Title ID:&emsp;<input type="text" name="id" required>&emsp;&emsp;
            Type:&emsp;<input type="text" name="type" required><br>
            Primary Title:&emsp;<input type="text" name="primaryTitle" required>&emsp;&emsp;
            Original Title:&emsp;<input type="text" name="originalTitle" required><br>
            Start Year:&emsp;<input type="text" name="startYear" required>&emsp;&emsp;
            End Year:&emsp;<input type="text" name="endYear"><br>
            Runtime (minutes):&emsp;<input type="text" name="runtime" required><br>
            Plot <br><textarea name="plot" style="width: 50%" required></textarea><br><br>
            Oscar (1/0):&emsp;<input type="text" name="oscar"><br>
            Genres (comma separated):&emsp;<input type="text" name="genres" required><br>
            Directors (comma separated):&emsp;<input type="text" name="directors" required><br>
            Writers (comma separated):&emsp;<input type="text" name="writers" required><br>
            Actors (comma separated):&emsp;<input type="text" name="actors" required><br>
            Rating:&emsp;<input type="text" name="rating" required>&emsp;&emsp;
            No of Votes:&emsp;<input type="text" name="votes" required><br><br>
            <button type="submit" name="add_title" value="Add" style="width: 150px">Add</button>
        </form>
    </div>
</div>
</body>
</html>
