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
    session_start();
    include 'db_connect.php';
    $listName = $_GET['list'];
    $username = $_SESSION['username'];
    echo "<h1 align='left' style='margin-top: 20px; margin-left: 50px; margin-bottom: 0'>$listName</h1>";
    echo "<h5 align='left' style='margin-left: 50px; margin-top: 10px'>A list by $username</h5>";
    echo "<br><div align='left'><a href='Delete_List.php?id=$listName'><button style='margin-left: 50px; margin-top: 0; width: 100px; background-color: #172c44'>Delete</button></a></div>";


$sql = "SELECT titleID FROM List WHERE username = :username AND listName = :listName";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':listName', $listName);

    $stmt->execute();

    $listedTitles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $count = count($listedTitles);

    echo "<h3 style='margin-top: 20px'>$count<br>Entries on this list</h3>";

    echo "<div class='poster-container'>";

    foreach ($listedTitles as $movie) {
        $imdbId = $movie['titleID'];
        echo "<div style='display: flex;flex-direction: column'><a href='Info.php?id=$imdbId'><img src='/Poster/$imdbId.jpg' alt='Movie Image' class='Lposter'></a>";
        echo "<a href='Delete_from_List.php?id=$imdbId&list=$listName'><button style='width: 220px; background-color: #172c44'>Delete</button></a></div>";
    }

    echo "</div>";
?>

