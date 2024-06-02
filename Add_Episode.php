<?php
session_start();
include 'Admin_Logged.php';
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_episode'])) {
    $episodeId = $_POST['episodeId'];
    $parentId = $_POST['parentId'];
    $season = $_POST['season'];
    $episode = $_POST['episode'];
    $title = $_POST['title'];
    $releaseDate = $_POST['releaseDate'];
    $runtime = $_POST['runtime'];

    try {
        $stmt = $conn->prepare("INSERT INTO episodes (episodeId, parentId, season, episode, title, releaseDate, runtime) VALUES (:episodeId, :parentId, :season, :episode, :title, :releaseDate, :runtime)");
        $stmt->execute([
            'episodeId' => $episodeId,
            'parentId' => $parentId,
            'season' => $season,
            'episode' => $episode,
            'title' => $title,
            'releaseDate' => $releaseDate,
            'runtime' => $runtime
        ]);

        header("Location: Add_Episode.php?sux=Episode added successfully!");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
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
    <title>Add Episode - IMRS</title>
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
        <a href="Add_Title.php">Add Entries</a><br>
        <a href="Add_Episode.php" style="background-color: #031020">Add Episode</a><br>
        <a href="Manage_Entries.php">Modify Entries</a><br>
        <a href="Manage_Episode.php">Modify Episode</a><br>
        <a href="Manage_reviews.php">Manage Reviews</a><br>
        <a href="Logout.php">Logout</a><br>
    </div>
    <div style="width: 100%">
        <h1>Add New Episode</h1><hr>
        <br>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
            <input type="text" name="episodeId" placeholder="Episode ID" required><br>
            <input type="text" name="parentId" placeholder="Parent ID" required><br>
            <input type="text" name="season" placeholder="Season" required><br>
            <input type="text" name="episode" placeholder="Episode" required><br>
            <input type="text" name="title" placeholder="Episode Title" required><br>
            <input type="text" name="releaseDate" placeholder="Release Date" required><br>
            <input type="text" name="runtime" placeholder="Runtime" required><br><br>
            <button type="submit" name="add_episode" style="width: 150px">Add Episode</button>
        </form>
        <br>
        <div id="success" class="success">
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
    </div>
</div>
</body>
</html>

