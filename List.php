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
            <a href="List.php" style="background-color: #305d90; border-radius: 5px">List</a>
            <a href="Reviews.php">Reviews</a>
            <a href="Ratings.php">Ratings</a>
            <a href="Stats.php">Stats</a>
            <a href="Your.php">For you</a>
            <a href="Profile_Settings.php">Edit Profile</a>
        </div>
        <div class="category">
            <h2 style="color: #cad6f1; text-align: center"><br>Your Lists</h2>
            <form action="new_list.php" method="POST" align="center">
                <label for="new_list">Create New List:</label>
                <input type="text" name="new_list" id="new_list" style="width: 200px" required>
                <button type="submit" style="width: 70px">Add</button>
            </form><br>
            <div id="error-list" class="error-message">
                <?php
                if (isset($_GET['errorLR'])) {
                    echo htmlspecialchars($_GET['errorLR']);
                }
                ?>
            </div>
            <div id="success-list" class="success">
                <?php
                if (isset($_GET['errorRR'])) {
                    echo htmlspecialchars($_GET['errorRR']);
                }
                ?>
            </div>
            <?php

            if (isset($_SESSION['username'])) {
                $username = $_SESSION['username'];

                try {
                    $sql = "SELECT listName FROM List_name WHERE username = :username";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                    $stmt->execute();

                    $lists = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                }
            } else {
                echo "Invalid request.";
            }
            ?>

            <div class="category-container">
                <a href="Fav_output.php">
                    <div class="category-card" style="width: 200px; height: 100px">
                        <h2>Favourites</h2>
                    </div></a>
                <a href="Watchlist_Output.php">
                    <div class="category-card" style="width: 200px; height: 100px">
                        <h2>Watchlist</h2>
                    </div></a>
                <?php
                if(!empty($lists)){
                    foreach ($lists as $list) {
                    echo '
                    <a href="List_output.php?list=' . urlencode($list['listName']) . '">
                        <div class="category-card" style="width: 200px; height: 100px">
                            <h2>' . htmlspecialchars($list['listName']) . '</h2>
                        </div>
                    </a>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
