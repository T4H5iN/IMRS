<?php
session_start();

include 'Admin_Logged.php';
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
    <title>Movie Management - IMRS</title>
</head>
<body>
<div class="header">
    <a href="Home.php"><img src="Illustrations/imrs.png" alt="Logo" class="logo"></a>
    <div class="dropP">
        <?php
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
<div style="display: flex">
    <div class="sidebar">
        <a style="margin-top: 50px" href="Admin_Dashboard.php">Admin Dashboard</a><br>
        <a href="Manage_Users.php">Manage Users</a><br>
        <a href="Add_Title.php" >Add Entries</a><br>
        <a href="Add_Episode.php" >Add Episode</a><br>
        <a href="Manage_Entries.php">Modify Entries</a><br>
        <a href="Manage_Episode.php" >Modify Episode</a><br>
        <a href="Manage_reviews.php" style="background-color: #031020">Manage Reviews</a><br>
        <a href="Logout.php">Logout</a><br>
    </div>
    <div style="width: 100%">
        <h1>Manage Reviews</h1><hr>
        <?php
        include 'db_connect.php';
        $query = "SELECT * FROM Review";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll();
        ?>
        <div id="success" class="success">
            <?php
            if (isset($_GET['sux'])) {
                echo htmlspecialchars($_GET['sux']);
            }
            ?>
        </div>
        <div id="success" class="error-message">
            <?php
            if (isset($_GET['suxx'])) {
                echo htmlspecialchars($_GET['suxx']);
            }
            ?>
        </div>
        <table align="center">
            <tr>
                <th>Title ID</th>
                <th>Username</th>
                <th>Comment</th>
                <th>Publish Date</th>
                <th>Actions</th>
            </tr>
            <?php
            foreach ($result as $row) {
                echo "<tr>";
                echo "<td>" . $row['titleID'] . "</td>";
                echo "<td>" . $row['username'] . "</td>";
                echo "<td>" . $row['comment'] . "</td>";
                echo "<td>" . $row['publishDate'] . "</td>";
                echo "<td><a href='Delete_Review.php?id=" . $row['titleID'] . "&u=" . $row['username'] . "'><button>Delete</button></a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>