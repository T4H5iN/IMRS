<?php
session_start();
include 'Admin_Logged.php';
include 'db_connect.php';

// Edit episode logic
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM episodes WHERE episodeId = :id");
    $stmt->execute(['id' => $edit_id]);
    $edit_episode = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_episode'])) {
    $episodeId = $_POST['episodeId'];
    $season = $_POST['season'];
    $episode = $_POST['episode'];
    $title = $_POST['title'];
    $releaseDate = $_POST['releaseDate'];
    $runtime = $_POST['runtime'];

    $stmt = $conn->prepare("UPDATE episodes SET season = :season, episode = :episode, title = :title, releaseDate = :releaseDate, runtime = :runtime WHERE episodeId = :episodeId");
    $stmt->execute([
        'season' => $season,
        'episode' => $episode,
        'title' => $title,
        'releaseDate' => $releaseDate,
        'runtime' => $runtime,
        'episodeId' => $episodeId
    ]);

    header("Location: Manage_Episode.php?sux=Episode updated successfully!");
    exit();
}

$searchQuery = '';
if (isset($_GET['title'])) {
    $searchQuery = $_GET['title'];
}

$stmt = $conn->prepare("SELECT COUNT(*) FROM episodes WHERE title LIKE :searchQuery");
$stmt->execute(['searchQuery' => "%$searchQuery%"]);
$totalRecords = $stmt->fetchColumn();
$recordsPerPage = 10;
$totalPages = ceil($totalRecords / $recordsPerPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $recordsPerPage;

$stmt = $conn->prepare("SELECT * FROM episodes WHERE title LIKE :searchQuery LIMIT :limit OFFSET :offset");
$stmt->bindValue(':searchQuery', "%$searchQuery%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $recordsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$episodes = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
        <a href="Manage_Episode.php" style="background-color: #031020">Modify Episode</a><br>
        <a href="Manage_reviews.php">Manage Reviews</a><br>
        <a href="Logout.php">Logout</a><br>
    </div>
    <div style="width: 100%">
        <h1>Manage Episodes</h1><hr>
        <form action="Manage_Episode.php" method="get" class="search-bar-container" style="width: 50%; margin-left: 450px;">
            <input type="text" name="title" placeholder="Search for a Episode..." class="search-bar" style="width: 100px">
            <button type="submit" class="search-button" style="width: 100px; margin-top: 25px">Search</button>
        </form>
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
        <?php if (isset($edit_episode)) : ?>
            <form method='post' action='' enctype='multipart/form-data'>
                <input type='hidden' name='episodeId' value='<?php echo $edit_episode['episodeId']; ?>'>
                Season: <input type='text' name='season' value='<?php echo $edit_episode['season']; ?>' required><br>
                Episode: <input type='text' name='episode' value='<?php echo $edit_episode['episode']; ?>' required><br>
                Title: <input type='text' name='title' value='<?php echo $edit_episode['title']; ?>' required><br>
                Release Date: <input type='text' name='releaseDate' value='<?php echo $edit_episode['releaseDate']; ?>' required><br>
                Runtime: <input type='text' name='runtime' value='<?php echo $edit_episode['runtime']; ?>' required><br>
                <button type='submit' name='update_episode' value='Update'>Update</button>
            </form>
        <?php endif; ?>
        <table border='1' align='center' style="width: 100%">
            <tr>
                <th>Episode ID</th>
                <th>Season</th>
                <th>Episode</th>
                <th>Title</th>
                <th>Release Date</th>
                <th>Runtime</th>
                <th>Action</th>
            </tr>

            <?php foreach ($episodes as $episode): ?>
                <tr>
                    <td><?php echo $episode['episodeId']; ?></td>
                    <td><?php echo $episode['season']; ?></td>
                    <td><?php echo $episode['episode']; ?></td>
                    <td><?php echo $episode['title']; ?></td>
                    <td><?php echo $episode['releaseDate']; ?></td>
                    <td><?php echo $episode['runtime']; ?></td>
                    <td>
                        <a href='Manage_Episode.php?edit_id=<?php echo $episode['episodeId']; ?>'><button>Edit</button></a>
                        <a href='Delete_Episode.php?id=<?php echo $episode['episodeId']; ?>'><button>Delete</button></a>
                    </td>
                </tr>
            <?php endforeach; ?>

        </table>
        <?php pagination($currentPage, $totalPages, $searchQuery); ?>
    </div>
</div>
</body>
</html>
<?php
function pagination($currentPage, $totalPages, $searchQuery): void
{
$queryParams = $_GET;
unset($queryParams['page']);
$queryString = http_build_query($queryParams);
if ($searchQuery) {
$queryString .= '&title=' . urlencode($searchQuery);
}

echo "<div class='pagination'>";
    $maxPagesToShow = 7;

    $startRange = max(1, $currentPage - floor($maxPagesToShow / 2));
    $endRange = min($totalPages, $startRange + $maxPagesToShow - 1);

    if ($currentPage > 1) {
    echo "<a href='?" . $queryString . "&page=" . ($currentPage - 1) . "'>&lt;</a>";
    }

    if ($startRange > 1) {
    echo "<a href='?" . $queryString . "&page=1'>1</a>";
    if ($startRange > 2) {
    echo "<span>...</span>";
    }
    }

    for ($i = $startRange; $i <= $endRange; $i++) {
    echo "<a href='?" . $queryString . "&page=$i' " . ($i == $currentPage ? "class='active'" : "") . ">$i</a> ";
    }

    if ($endRange < $totalPages) {
    if ($endRange < $totalPages - 1) {
    echo "<span>...</span>";
    }
    echo "<a href='?" . $queryString . "&page=$totalPages'>$totalPages</a>";
    }

    if ($currentPage < $totalPages) {
    echo "<a href='?" . $queryString . "&page=" . ($currentPage + 1) . "'>&gt;</a>";
    }

    echo "</div>";
}
?>