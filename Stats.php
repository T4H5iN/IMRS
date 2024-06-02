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
        .circle-progress-container {
            position: relative;
            width: 200px;
            height: 200px;
        }
        #progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
        #progress-text1 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
        #progress-text2 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }
        #progress-text3 {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            font-size: 16px;
            font-weight: bold;
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
            <a href="Reviews.php">Reviews</a>
            <a href="Ratings.php">Ratings</a>
            <a href="Stats.php" style="background-color: #305d90; border-radius: 5px">Stats</a>
            <a href="Your.php">For you</a>
            <a href="Profile_Settings.php">Edit Profile</a>
        </div>
        <?php
            session_start();
            include 'db_connect.php';

            $username = $_SESSION['username'];

            try {
                $typeCountSql = "
                    SELECT type, COUNT(combined.id) as count
                    FROM (
                        SELECT titles.id, titles.type FROM titles
                        JOIN userRating ON titles.id = userRating.titleid
                        WHERE userRating.username = :username
                        UNION
                        SELECT titles.id, titles.type FROM titles
                        JOIN List ON titles.id = List.titleID
                        WHERE List.username = :username
                        UNION
                        SELECT titles.id, titles.type FROM titles
                        JOIN Favourite ON titles.id = Favourite.titleid
                        WHERE Favourite.username = :username
                    ) as combined
                    GROUP BY type
                ";


                $stmt = $conn->prepare($typeCountSql);
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $typeCounts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $movieCount = 0;
                $tvSeriesCount = 0;
                $tvMiniSeriesCount = 0;

                foreach ($typeCounts as $row) {
                    if ($row['type'] == 'movie') {
                        $movieCount = $row['count'];
                    } elseif ($row['type'] == 'tvSeries') {
                        $tvSeriesCount = $row['count'];
                    } elseif ($row['type'] == 'tvMiniSeries') {
                        $tvMiniSeriesCount = $row['count'];
                    }
                }

                // Query to calculate total watch hours
                $watchHourSql = "
                    SELECT SUM(runtime) as totalWatchHour
                    FROM (
                        SELECT DISTINCT titles.id, titles.runtime FROM titles
                        JOIN userRating ON titles.id = userRating.titleid
                        WHERE userRating.username = :username
                        UNION
                        SELECT DISTINCT titles.id, titles.runtime FROM titles
                        JOIN List ON titles.id = List.titleID
                        WHERE List.username = :username
                        UNION
                        SELECT DISTINCT titles.id, titles.runtime FROM titles
                        JOIN Favourite ON titles.id = Favourite.titleid
                        WHERE Favourite.username = :username
                    ) as combined
                ";

                $stmt = $conn->prepare($watchHourSql);
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $totalWatchHour = $stmt->fetchColumn();

            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                $movieCount = 0;
                $tvSeriesCount = 0;
                $tvMiniSeriesCount = 0;
                $totalWatchHour = 0;
            }
        ?>

        <h1>All-Time Stats</h1><hr>
        <div class="stats-counter">
            <span><?php echo $movieCount; ?><br>Movies</span>
            <span><?php echo $tvSeriesCount; ?><br>TV Series</span>
            <span><?php echo $tvMiniSeriesCount; ?><br>TV Mini Series</span>
            <span><?php echo $totalWatchHour; ?><br>Watch Hour</span>
        </div>
        <br><br><div style="display: flex; margin-top: 10px; margin-bottom: 10px; justify-content: center; gap: 50px">
            <canvas id="rating-chart" style="max-width: 400px; max-height: 400px"></canvas>
            <canvas id="genre-chart" style="max-width: 400px; max-height: 400px"></canvas>
        </div>
        <?php
        session_start();
        include 'db_connect.php';

        $username = $_SESSION['username'];

        try {
            $typeCountSql = "SELECT COUNT(DISTINCT combined.id) as count
                                FROM (
                                    SELECT titles.id, titles.type FROM titles
                                    JOIN userRating ON titles.id = userRating.titleid
                                    WHERE userRating.username = :username
                                    UNION
                                    SELECT titles.id, titles.type FROM titles
                                    JOIN List ON titles.id = List.titleID
                                    WHERE List.username = :username
                                    UNION
                                    SELECT titles.id, titles.type FROM titles
                                    JOIN Favourite ON titles.id = Favourite.titleid
                                    WHERE Favourite.username = :username
                                ) as combined
                                JOIN award ON combined.id = award.id
                                WHERE type = 'movie'
                            ";

            $stmt = $conn->prepare($typeCountSql);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $topMovieCount = $stmt->fetchColumn();

            $typeCountSql1 = "SELECT COUNT(DISTINCT combined.id) as count
                                FROM (
                                    SELECT titles.id, titles.type FROM titles
                                    JOIN userRating ON titles.id = userRating.titleid
                                    WHERE userRating.username = :username
                                    UNION
                                    SELECT titles.id, titles.type FROM titles
                                    JOIN List ON titles.id = List.titleID
                                    WHERE List.username = :username
                                    UNION
                                    SELECT titles.id, titles.type FROM titles
                                    JOIN Favourite ON titles.id = Favourite.titleid
                                    WHERE Favourite.username = :username
                                ) as combined
                                JOIN award ON combined.id = award.id
                                WHERE type like '%series%'
                            ";

            $stmt1 = $conn->prepare($typeCountSql1);
            $stmt1->bindParam(':username', $username);
            $stmt1->execute();
            $topShowCount = $stmt1->fetchColumn();

            $typeCountSql2 = "SELECT COUNT(DISTINCT combined.id) as count
                                FROM (
                                    SELECT titles.id, titles.oscar FROM titles
                                    JOIN userRating ON titles.id = userRating.titleid
                                    WHERE userRating.username = :username
                                    UNION
                                    SELECT titles.id, titles.oscar FROM titles
                                    JOIN List ON titles.id = List.titleID
                                    WHERE List.username = :username
                                    UNION
                                    SELECT titles.id, titles.oscar FROM titles
                                    JOIN Favourite ON titles.id = Favourite.titleid
                                    WHERE Favourite.username = :username
                                ) as combined
                                WHERE oscar = 1
                            ";

            $stmt2 = $conn->prepare($typeCountSql2);
            $stmt2->bindParam(':username', $username);
            $stmt2->execute();
            $pOscarCount = $stmt2->fetchColumn();

            $typeCountSql3 = "SELECT COUNT(DISTINCT combined.id) as count
                                FROM (
                                    SELECT titles.id, titles.oscar FROM titles
                                    JOIN userRating ON titles.id = userRating.titleid
                                    WHERE userRating.username = :username
                                    UNION
                                    SELECT titles.id, titles.oscar FROM titles
                                    JOIN List ON titles.id = List.titleID
                                    WHERE List.username = :username
                                    UNION
                                    SELECT titles.id, titles.oscar FROM titles
                                    JOIN Favourite ON titles.id = Favourite.titleid
                                    WHERE Favourite.username = :username
                                ) as combined
                                WHERE oscar = 2
                            ";

            $stmt3 = $conn->prepare($typeCountSql3);
            $stmt3->bindParam(':username', $username);
            $stmt3->execute();
            $aOscarCount = $stmt3->fetchColumn();

            $typeCountSql4 = "SELECT COUNT(*) FROM titles where oscar=1";

            $stmt4 = $conn->prepare($typeCountSql4);
            $stmt4->execute();
            $totalPOscarCount = $stmt4->fetchColumn();

            $typeCountSql5 = "SELECT COUNT(*) FROM titles where oscar=2";

            $stmt5 = $conn->prepare($typeCountSql5);
            $stmt5->execute();
            $totalAOscarCount = $stmt5->fetchColumn();

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            $topMovieCount = 0;
            $topShowCount = 0;
            $pOscarCount = 0;
            $aOscarCount = 0;
        }
        ?>
        <br><div class="stats-counter" style="margin-bottom: 10px">
            <div class="circle-progress-container">
                <canvas id="progress-chart"></canvas>
                <div id="progress-text"><?php echo $topMovieCount; ?><br>Top 250 Movies</div>
            </div>
            <div class="circle-progress-container">
                <canvas id="progress-chart1"></canvas>
                <div id="progress-text1"><?php echo $topShowCount; ?><br>Top 250 Shows</div>
            </div>
            <div class="circle-progress-container">
                <canvas id="progress-chart2"></canvas>
                <div id="progress-text2"><?php echo $pOscarCount; ?><br>Oscar Best Picture</div>
            </div>
            <div class="circle-progress-container">
                <canvas id="progress-chart3"></canvas>
                <div id="progress-text3"><?php echo $aOscarCount; ?><br>Oscar Best Animation</div>
            </div>
        </div>

        <?php
            include 'Functions.php';
        ?>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ratingData = [
        <?php
        $start = 1;
        $end = 10;
        for ($i = $start; $i <= $end; $i++) {
            $percentage = calculatePercentage($i, $conn);
            echo "$percentage,";
        }
        ?>
    ];

    const ratingChartCanvas = document.getElementById('rating-chart').getContext('2d');
    const ratingChart = new Chart(ratingChartCanvas, {
        type: 'doughnut',
        data: {
            labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10'],
            datasets: [{
                data: ratingData,
                backgroundColor: [
                    '#7AA9E2',
                    '#5F98DC',
                    '#4586D6',
                    '#2D75CE',
                    '#2766B3',
                    '#215798',
                    '#1C487E',
                    '#163963',
                    '#102949',
                    '#0A1A2E'
                ],
                borderColor: [
                    '#cad6f1'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Rating Stats',
                    font: {
                        size: 16
                    },
                    color: '#CAD6F1'
                }
            }
        }
    });

    const genreData = [
        <?php
        $genresToDisplay = ['Action', 'Adventure', 'Animation', 'Biography', 'Comedy', 'Crime', 'Drama', 'Family', 'Fantasy', 'History', 'Horror', 'Music', 'Musical', 'Mystery', 'Romance', 'Sci-Fi', 'Sport', 'Thriller', 'War', 'Western'];
        foreach ($genresToDisplay as $genre) {
            $genrePercentage = calculateGenrePercentage($genre, $conn);
            echo "$genrePercentage,";
        }
        ?>
    ];

    const genreChartCanvas = document.getElementById('genre-chart').getContext('2d');
    const genreChart = new Chart(genreChartCanvas, {
        type: 'doughnut',
        data: {
            labels: ['Action', 'Adventure', 'Animation', 'Biography', 'Comedy', 'Crime', 'Drama', 'Family', 'Fantasy', 'History', 'Horror', 'Music', 'Musical', 'Mystery', 'Romance', 'Sci-Fi', 'Sport', 'Thriller', 'War', 'Western'],
            datasets: [{
                data: genreData,
                backgroundColor: [
                    '#7AA9E2',
                    '#5F98DC',
                    '#4586D6',
                    '#2D75CE',
                    '#2766B3',
                    '#215798',
                    '#1C487E',
                    '#163963',
                    '#102949',
                    '#0A1A2E'
                ],
                borderColor: [
                    '#cad6f1'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Genre Stats',
                    font: {
                        size: 16
                    },
                    color: '#CAD6F1'
                }
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('progress-chart').getContext('2d');
        const totalMovies = 250;
        const watchedMovies = <?php echo $topMovieCount; ?>;
        const progress = (watchedMovies / totalMovies) * 100;

        const data = {
            datasets: [{
                data: [progress, 100 - progress],
                backgroundColor: ['#163963', '#cad6f1'],
                borderWidth: 0
            }]
        };

        const options = {
            cutout: '80%',
            plugins: {
                tooltip: {
                    enabled: false
                }
            }
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: options
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('progress-chart1').getContext('2d');
        const totalMovies = 250;
        const watchedMovies = <?php echo $topShowCount; ?>;
        const progress = (watchedMovies / totalMovies) * 100;

        const data = {
            datasets: [{
                data: [progress, 100 - progress],
                backgroundColor: ['#163963', '#cad6f1'],
                borderWidth: 0
            }]
        };

        const options = {
            cutout: '80%',
            plugins: {
                tooltip: {
                    enabled: false
                }
            }
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: options
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('progress-chart2').getContext('2d');
        const totalMovies = <?php echo $totalPOscarCount; ?>;
        const watchedMovies = <?php echo $pOscarCount; ?>;
        const progress = (watchedMovies / totalMovies) * 100;

        const data = {
            datasets: [{
                data: [progress, 100 - progress],
                backgroundColor: ['#163963', '#cad6f1'],
                borderWidth: 0
            }]
        };

        const options = {
            cutout: '80%',
            plugins: {
                tooltip: {
                    enabled: false
                }
            }
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: options
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('progress-chart3').getContext('2d');
        const totalMovies = <?php echo $totalAOscarCount; ?>;
        const watchedMovies = <?php echo $aOscarCount; ?>;
        const progress = (watchedMovies / totalMovies) * 100;

        const data = {
            datasets: [{
                data: [progress, 100 - progress],
                backgroundColor: ['#163963', '#cad6f1'],
                borderWidth: 0
            }]
        };

        const options = {
            cutout: '80%',
            plugins: {
                tooltip: {
                    enabled: false
                }
            }
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: options
        });
    });


</script>
</body>
</html>
