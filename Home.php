<?php
include 'User_Logged.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Style.css">
    <title>Popular Movies of All Time - IMRS</title>
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
    session_start();
    include 'Functions.php';
    include 'db_connect.php';

    $username = $_SESSION['username'];

    echo "<div><h1>Welcome $username</h1></div>";
?>
<div class="relContainer" style="background-color: #0f2845; margin-right: 160px; margin-left: 160px">
    <h2 style="color: #cad6f1; text-align: left; margin-left: 15px"><br>Hot Release</h2>
    <div class="imgContainer">
        <div class="slide_div" id="slide_1">
            <a href='Info.php?id=tt11389872'><img src="Poster/tt11389872.jpg" alt="" class="movie-poster" id="img1" height="300" width="auto"></a>
        </div>
        <div class="slide_div" id="slide_2">
            <a href='Info.php?id=tt1684562'><img src="Poster/tt1684562.jpg" alt="" class="movie-poster" id="img2" height="300" width="auto"></a>
        </div>
        <div class="slide_div" id="slide_3">
            <a href='Info.php?id=tt2788316'><img src="Poster/tt2788316.jpg" alt="" class="movie-poster" id="img3" height="300" width="auto"></a>
        </div>
        <div class="slide_div" id="slide_4">
            <a href='Info.php?id=tt12037194'><img src="Poster/tt12037194.jpg" alt="" class="movie-poster" id="img4" height="300" width="auto"></a>
        </div>
        <div class="slide_div" id="slide_5">
            <a href='Info.php?id=tt11152168'><img src="Poster/tt11152168.jpg" alt="" class="movie-poster" id="img5" height="300" width="auto"></a>
        </div>
        <div class="slide_div" id="slide_6">
            <a href='Info.php?id=tt5177120'><img src="Poster/tt5177120.jpg" alt="" class="movie-poster" id="img6" height="300" width="auto"></a>
        </div>
        <div class="slide_div" id="slide_6">
            <a href='Info.php?id=tt16026746'><img src="Poster/tt16026746.jpg" alt="" class="movie-poster" id="img6" height="300" width="auto"></a>
        </div>
    </div>
</div>

<div class="category">
    <h2 style="color: #cad6f1; text-align: left; margin-left: 30px"><br>Browse by Genre</h2>
    <div class="category-container">
        <a href="show_genre.php?genre=Action">
            <div class="category-card">
            <h3>Action</h3>
        </div></a>
        <a href="show_genre.php?genre=Adventure">
            <div class="category-card">
            <h3>Adventure</h3>
        </div>
        <a href="show_genre.php?genre=Animation">
            <div class="category-card">
            <h3>Animation</h3>
        </div>
        <a href="show_genre.php?genre=Comedy">
            <div class="category-card">
            <h3>Comedy</h3>
        </div>
        <a href="show_genre.php?genre=Crime">
            <div class="category-card">
            <h3>Crime</h3>
        </div>
        <a href="show_genre.php?genre=Drama">
            <div class="category-card">
            <h3>Drama</h3>
        </div>
        <a href="show_genre.php?genre=Family">
            <div class="category-card">
            <h3>Family</h3>
        </div>
        <a href="show_genre.php?genre=Fantasy">
            <div class="category-card">
            <h3>Fantasy</h3>
        </div>
        <a href="show_genre.php?genre=History">
            <div class="category-card">
            <h3>History</h3>
        </div>
    </div>
</div>
<div class="category">
    <h2 style="color: #cad6f1; text-align: center; margin-left: 30px"><br>IMDB Top 250</h2>
    <div class="category-container">
        <a href="Top.php?type=movie">
            <div class="category-card" style="width: 300px">
                <h3>Top 250 Movies</h3>
            </div></a>
        <a href="Top.php?type=series">
            <div class="category-card" style="width: 300px">
                <h3>Top 250 Shows</h3>
            </div></a>
    </div>
</div>
<div class="category">
    <h2 style="color: #cad6f1; text-align: center; margin-left: 30px"><br>Oscar Winner</h2>
    <div class="category-container">
        <a href="Oscar.php?type=b">
            <div class="category-card" style="width: 300px">
                <h3>Oscar Best Picture</h3>
            </div></a>
        <a href="Oscar.php?type=a">
            <div class="category-card" style="width: 300px">
                <h3>Oscar Best Animation</h3>
            </div></a>
    </div>
</div>
</body>
</html>
