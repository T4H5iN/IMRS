<?php
include 'User_Logged.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Style.css">
    <title>Popular TV Series of All Time - IMRS</title>
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

<?php
include 'Functions.php';
include 'db_connect.php';

if(isset($_GET['id'])) {
    $movieId = $_GET['id'];
    try {

        $sql = "SELECT tb.id, type, primaryTitle, originalTitle, startYear, runtime, rating, noOfVotes, plot FROM titles tb NATURAL JOIN rating r WHERE tb.id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $movieId);
        $stmt->execute();

        if($movieDetails = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<div class='info-container'>";
            Info($movieDetails);
            Info_output($movieDetails);

            if ($movieDetails['type'] == 'tvSeries' || $movieDetails['type'] == 'tvMiniSeries') {
                try {
                    if (isset($_GET['season'])) {
                        $selectedSeason = $_GET['season'];
                        // Fetch episodes for the selected season
                        $sql = "SELECT episode, title AS episodeTitle, runtime from episodes WHERE parentID = :id AND season = :season ORDER BY episode";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':id', $movieId);
                        $stmt->bindParam(':season', $selectedSeason);
                        $stmt->execute();
                        $episodes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $selectedSeason = '';
                    }

                    $sql = "SELECT DISTINCT season FROM episodes WHERE parentID = :id ORDER BY season";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':id', $movieId);
                    $stmt->execute();

                    $seasons = $stmt->fetchAll(PDO::FETCH_COLUMN);

                    echo "<div style='margin: 50px 0 50px 0'>";
                    echo "<h2 align='center'>Episode List</h2><hr width='75%'>";
                    echo "<form method='get' style='text-align: center; margin-bottom: 15px'>";
                    echo "<input type='hidden' name='id' value='$movieId'>";
                    echo "<label for='season-dropdown'>Select Season:</label>";
                    echo "<select id='season-dropdown' name='season' onchange='this.form.submit()'>";
                    echo "<option value='' selected disabled>Select a season</option>";
                    foreach ($seasons as $season) {
                        $selected = ($selectedSeason == $season) ? 'selected' : '';
                        echo "<option value='{$season}' $selected>Season {$season}</option>";
                    }
                    echo "</select>";
                    echo "</form>";


                    if (isset($episodes)) {
                        echo "<table border='1' style='margin: 0 auto;'>"; // Center-align the table
                        echo "<tr><th>Episode</th><th>Title</th><th>Runtime</th></tr>";
                        foreach ($episodes as $episode) {
                            echo "<tr>";
                            echo "<td>{$episode['episode']}</td>";
                            echo "<td>{$episode['episodeTitle']}</td>";
                            echo "<td>{$episode['runtime']} minutes</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    }

                    echo "</div>";
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
            }

            echo "<div class='comment-box'>
                        <h2 align='center'>Comments</h2><hr style='margin-bottom: 20px'>";
            $sql = "SELECT username, comment, publishDate FROM Review WHERE titleID = :movieId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':movieId', $movieId);
            $stmt->execute();
            while($comment = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='comment'>
                                    <h3 style='margin-bottom: 30px'>" . $comment['username'] . "</h3>
                                    <p align='justify'>" . $comment['comment'] . "</p>
                                    <p align='right' style='font-size: 15px'>" . $comment['publishDate'] . "</p>
                                </div>";
            }
            echo "<form action='submit_comment.php?titleID=$movieId' method='post'>
                                <br><label for='comment'>Add a comment</label><br>
                                <textarea id='comment' name='comment'></textarea><br><br>
                                <button type='submit'>Submit</button>
                            </form>
                        </div>";
        } else {
            echo "Movie not found";
        }
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
} else {
    echo "Movie ID not provided";
}
?>
</body>
</html>
