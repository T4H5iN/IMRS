<?php
include 'User_Logged.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Style.css">
    <style>
        .Lposter {
            max-height: 250px;
            width: auto;
            object-fit: cover;
        }
    </style>
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
<div style="display: flex;margin-top: 5px; margin-bottom: 5px">
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
            <a href="Reviews.php">Reviews</a>
            <a href="Ratings.php">Ratings</a>
            <a href="Stats.php">Stats</a>
            <a href="Your.php" style="background-color: #305d90; border-radius: 5px">For you</a>
            <a href="Profile_Settings.php">Edit Profile</a>
        </div>

        <?php
        include 'Functions.php';
        if (isset($_SESSION['username'])) {
            $username = $_SESSION['username'];

            echo "<h2>Top-rated titles you missed</h2><hr>";
            $topRatedMovies = getTopRated($conn, 'movie', 5);
            $topRatedShows = getTopRated($conn, 'show', 5);
            echo "<div class='poster-container'>";
            foreach ($topRatedMovies as $movie) {
                $imdbId = $movie['id'];
                $rating = $movie['rating'];
                $title = $movie['primaryTitle'];
                echo "<a href='Info.php?id=$imdbId'><img src='/Poster/$imdbId.jpg' alt='$title' class='Lposter'></a>";
            }
            foreach ($topRatedShows as $movie) {
                $imdbId = $movie['id'];
                $rating = $movie['rating'];
                $title = $movie['primaryTitle'];
                echo "<a href='Info.php?id=$imdbId'><img src='/Poster/$imdbId.jpg' alt='$title' class='Lposter'></a>";
            }
            echo "</div>";
            echo "<h2>Most popular titles you missed</h2><hr>";
            $popularMovies = getPopular($conn, 'movie', 5);
            $popularShows = getPopular($conn, 'show', 5);
            echo "<div class='poster-container'>";
            foreach ($popularMovies as $movie) {
                $imdbId = $movie['id'];
                $rating = $movie['rating'];
                $title = $movie['primaryTitle'];
                echo "<a href='Info.php?id=$imdbId'><img src='/Poster/$imdbId.jpg' alt='$title' class='Lposter'></a>";
            }
            foreach ($popularShows as $movie) {
                $imdbId = $movie['id'];
                $rating = $movie['rating'];
                $title = $movie['primaryTitle'];
                echo "<a href='Info.php?id=$imdbId'><img src='/Poster/$imdbId.jpg' alt='$title' class='Lposter'></a>";
            }
            echo "</div>";

            $recommendedMovies = recommendContent($conn, $username, 'movie');
            $recommendedShows = recommendContent($conn, $username, 'show');

            if (!empty($recommendedMovies)) {
                echo "<h2>Based on your most watched genre</h2><hr>";
                echo "<div class='poster-container'>";
                foreach ($recommendedMovies as $movie) {
                    $imdbId = $movie['id'];
                    $rating = $movie['rating'];
                    $title = $movie['primaryTitle'];
                    echo "<a href='Info.php?id=$imdbId'><img src='/Poster/$imdbId.jpg' alt='$title' class='Lposter'></a>";
                }

                if (!empty($recommendedShows)) {
                    foreach ($recommendedShows as $show) {
                        $imdbId = $show['id'];
                        $rating = $show['rating'];
                        $title = $show['primaryTitle'];
                        echo "<a href='Info.php?id=$imdbId'><img src='/Poster/$imdbId.jpg' alt='$title' class='Lposter'></a>";
                    }

                    echo "</div>";
                }

            } else {
                echo "<p>Start watching movies to get recommendation</p>";
            }
        } else {
            echo "<p>User not logged in.</p>";
        }
        ?>

    </div>
</div>
</body>
</html>
