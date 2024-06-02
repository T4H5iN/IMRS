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
            <a href="List.php">List</a>
            <a href="Reviews.php">Reviews</a>
            <a href="Ratings.php">Ratings</a>
            <a href="Stats.php">Stats</a>
            <a href="Your.php">For you</a>
            <a href="Profile_Settings.php" style="background-color: #305d90; border-radius: 5px">Edit Profile</a>
        </div>
        <h2>Change Profile Picture</h2>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="profile_picture">Choose a profile picture:</label>
            <input type="file" name="profile_picture" id="profile_picture" accept=".jpg, .jpeg, .png, .gif" style="width: 300px">
            <button type="submit" style="width: 70px; height: 32px; font-size: 17px; margin-left: 10px">Upload</button>
        </form>
        <br>
        <h2>Change Password</h2>
        <form action="update_profile.php" method="post" align="right" style="margin-right: 725px">
            <label for="old_password">Old Password:</label>
            <input type="password" name="old_password" id="old_password" style="width: 300px" required><br>
            <label for="new_password">New Password:</label>
            <input type="password" name="new_password" id="new_password" style="width: 300px" required><br>
            <label for="confirm_password">Confirm New Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" style="width: 300px" required><br>
            <button type="submit" style="width: 200px; height: 32px; font-size: 17px; margin-top: 10px">Change Password</button>
        </form><br>
        <div id="error-password-requirement" class="error-message">
            <?php
            if (isset($_GET['errorPR'])) {
                echo htmlspecialchars($_GET['errorPR']);
            }
            ?>
        </div>
        <div id="success-password-requirement" class="success">
            <?php
            if (isset($_GET['yesPR'])) {
                echo htmlspecialchars($_GET['yesPR']);
            }
            ?>
        </div>
        <br>
        <h2>Change Email</h2>
        <form action="update_profile.php" method="post" align="right" style="margin-right: 725px">
            <label for="new_email">New Email:</label>
            <input type="email" name="new_email" id="new_email" style="width: 300px" required><br>
            <label for="confirm_email">Confirm New Email:</label>
            <input type="email" name="confirm_email" id="confirm_email" style="width: 300px" required><br>
            <button type="submit" style="width: 200px; height: 32px; font-size: 17px; margin-top: 5px; margin-bottom: 10px">Change Email</button>
        </form><br>
        <div id="error-mail-requirement" class="error-message">
            <?php
            if (isset($_GET['errorMR'])) {
                echo htmlspecialchars($_GET['errorMR']);
            }
            ?>
        </div>
        <div id="success-mail-requirement" class="success">
            <?php
            if (isset($_GET['yesMR'])) {
                echo htmlspecialchars($_GET['yesMR']);
            }
            ?>
        </div><br>
    </div>
</div>
</body>
</html>
