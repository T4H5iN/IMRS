<?php
include 'User_Logged.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Style.css">
    <title>User Profile - IMRS</title>
</head>

<body>
<div class="header">
    <a href="Home.php"><img src="Illustrations/imrs.png" alt="Logo" class="logo"></a>
    <form action="Advanced_Search.php" method="get" class="search-bar-container">
        <input type="text" name="title" placeholder="Search for a Movie..." class="search-bar">
        <button type="submit" class="search-button"><img src="Illustrations/search.png" alt="Search Icon" height="30" width="30"></button>
    </form>
    <div class="dropP">
        <?php
        session_start();
        include 'db_connect.php';

        $username = $_SESSION['username'];

        $query = "SELECT DATEDIFF(CURDATE(), regDate) as daysSinceRegistration FROM userinfo WHERE username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $daysSinceRegistration = $stmt->fetchColumn();
        /*
        $username = $_SESSION['username'];

        $query = "SELECT DATE_FORMAT(regDate, '%d %b %Y') as regDate FROM userinfo WHERE username = :username";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $year = $stmt->fetchColumn();
        */
        $username = $_SESSION['username'];
        $imageFiles = glob("Profile_Image/$username.*");
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
<div style="display: flex;margin-top: 5px">
    <div class="profile">
        <div class="profile-card">
            <?php
            if (!empty($imageFiles)) {
                $imagePath = $imageFiles[0];
                echo "<img src='$imagePath' class='profile-img' align='left' alt='Profile Image'>";
            } else {
                echo "<img src='Profile_Image/user.png' class='profile-img' align='left' alt='Profile Image'>";
            }
            ?>
            <div class="profile-info">
                <h1 align="left"><?php echo $username; ?></h1>
                <div align="left">Member for <?php echo $daysSinceRegistration; ?> days</div>
            </div>
        </div>
        <div class="navbar">
            <a href="Profile.php">Overview</a>
            <a href="List.php">List</a>
            <a href="Reviews.php" style="background-color: #305d90; border-radius: 5px">Reviews</a>
            <a href="Ratings.php">Ratings</a>
            <a href="Stats.php">Stats</a>
            <a href="Your.php">For you</a>
            <a href="Profile_Settings.php">Edit Profile</a>
        </div>
        <div class='comment-box'>
            <h2 align='center'>My Reviews</h2><hr>
            <?php
            $username = $_SESSION['username'];

            $sql = "SELECT titleID, comment, publishDate FROM Review WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $count = $stmt->rowCount();
            echo "<h3 style='margin: 20px 5px 20px 5px; padding: 10px 0 10px 0; background-color: #0a1a2e; border-radius: 5px'>$count<br>Reviwed Titles</h3>";
            while($comment = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $imdbId = $comment['titleID'];
                echo "<div class='review-item' style='display: flex; align-items: start; gap: 20px; margin-bottom: 20px;'>
                 <a href='Info.php?id=$imdbId'><img src='/Poster/$imdbId.jpg' alt='Movie Image' class='movie-image'></a>
                <div class='comment' style='background-color: #0a1b2d; width: 100%'>
                <p align='justify'>" . $comment['comment'] . "</p>
                <p align='right' style='font-size: 15px'>" . $comment['publishDate'] . "</p>
                </div>
                </div>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
