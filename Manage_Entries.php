<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
session_start();
include 'Admin_Logged.php';
include 'db_connect.php';

// Edit movie logic
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $conn->prepare("
        SELECT t.*, r.*, GROUP_CONCAT(DISTINCT g.genre ORDER BY g.genre SEPARATOR ', ') AS genres, 
                      GROUP_CONCAT(DISTINCT d.director ORDER BY d.director SEPARATOR ', ') AS directors, 
                      GROUP_CONCAT(DISTINCT w.writer ORDER BY w.writer SEPARATOR ', ') AS writers, 
                      GROUP_CONCAT(DISTINCT a.actor ORDER BY a.actor SEPARATOR ', ') AS actors
        FROM titles t
        LEFT JOIN genres g ON t.id = g.id
        LEFT JOIN directors d ON t.id = d.id
        LEFT JOIN writers w ON t.id = w.id
        LEFT JOIN actors a ON t.id = a.id
        LEFT JOIN rating r ON t.id = r.id
        WHERE t.id = :id
        GROUP BY t.id
    ");
    $stmt->execute(['id' => $edit_id]);
    $edit_movie = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update movie logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_movie'])) {
    $id = $_POST['id'];
    $primaryTitle = $_POST['primaryTitle'];
    $originalTitle = $_POST['originalTitle'];
    $type = $_POST['type'];
    $startYear = $_POST['startYear'];
    $endYear = $_POST['endYear'];
    if (empty($endYear)) {
        $endYear = NULL;
    }
    $runtime = $_POST['runtime'];
    $genre = $_POST['genre'];
    $director = $_POST['director'];
    $writer = $_POST['writer'];
    $actor = $_POST['actor'];
    $rating = $_POST['rating'];
    $noOfVotes = $_POST['noOfVotes'];
    $plot = $_POST['plot'];

    $conn->beginTransaction();

    $stmt = $conn->prepare("UPDATE titles SET primaryTitle = :primaryTitle, originalTitle = :originalTitle, type = :type, startYear = :startYear, endYear = :endYear, runtime = :runtime, plot = :plot WHERE id = :id");
    $stmt->execute([
        'primaryTitle' => $primaryTitle,
        'originalTitle' => $originalTitle,
        'type' => $type,
        'startYear' => $startYear,
        'endYear' => $endYear,
        'runtime' => $runtime,
        'plot' => $plot,
        'id' => $id
    ]);

    $stmt = $conn->prepare("UPDATE rating SET rating = :rating, noOfVotes = :noOfVotes WHERE id = :id");
    $stmt->execute(['rating' => $rating, 'noOfVotes' => $noOfVotes, 'id' => $id]);


    // Update genres
    $stmt = $conn->prepare("DELETE FROM genres WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $genres = explode(', ', $genre);
    foreach ($genres as $g) {
        $stmt = $conn->prepare("INSERT INTO genres (id, genre) VALUES (:id, :genre)");
        $stmt->execute(['id' => $id, 'genre' => $g]);
    }

    // Update directors
    $stmt = $conn->prepare("DELETE FROM directors WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $directors = explode(', ', $director);
    foreach ($directors as $d) {
        $stmt = $conn->prepare("INSERT INTO directors (id, director) VALUES (:id, :director)");
        $stmt->execute(['id' => $id, 'director' => $d]);
    }

    // Update writers
    $stmt = $conn->prepare("DELETE FROM writers WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $writers = explode(', ', $writer);
    foreach ($writers as $w) {
        $stmt = $conn->prepare("INSERT INTO writers (id, writer) VALUES (:id, :writer)");
        $stmt->execute(['id' => $id, 'writer' => $w]);
    }

    // Update actors
    $stmt = $conn->prepare("DELETE FROM actors WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $actors = explode(', ', $actor);
    foreach ($actors as $a) {
        $stmt = $conn->prepare("INSERT INTO actors (id, actor) VALUES (:id, :actor)");
        $stmt->execute(['id' => $id, 'actor' => $a]);
    }

    // Handle poster upload
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        $posterTmpPath = $_FILES['poster']['tmp_name'];
        $posterPath = 'Poster/' . $id . '.jpg';
        move_uploaded_file($posterTmpPath, $posterPath);
    }

    $conn->commit();

    header("Location: Manage_Entries.php?sux=Movie updated successfully!");
    exit();
}


$searchQuery = '';
if (isset($_GET['title'])) {
    $searchQuery = $_GET['title'];
}


// Pagination logic
$stmt = $conn->prepare("
    SELECT COUNT(*) FROM titles t
    LEFT JOIN genres g ON t.id = g.id
    LEFT JOIN directors d ON t.id = d.id
    LEFT JOIN writers w ON t.id = w.id
    LEFT JOIN actors a ON t.id = a.id
    LEFT JOIN rating r ON t.id = r.id
    WHERE t.primaryTitle LIKE :searchQuery
    GROUP BY t.id
");
$stmt->execute(['searchQuery' => "%$searchQuery%"]);
$totalRecords = $stmt->rowCount();
$recordsPerPage = 10;
$totalPages = ceil($totalRecords / $recordsPerPage);
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $recordsPerPage;

// Fetch movies with pagination
$stmt = $conn->prepare("
    SELECT t.*,r.*,  GROUP_CONCAT(DISTINCT g.genre ORDER BY g.genre SEPARATOR ', ') AS genres, 
                  GROUP_CONCAT(DISTINCT d.director ORDER BY d.director SEPARATOR ', ') AS directors, 
                  GROUP_CONCAT(DISTINCT w.writer ORDER BY w.writer SEPARATOR ', ') AS writers, 
                  GROUP_CONCAT(DISTINCT a.actor ORDER BY a.actor SEPARATOR ', ') AS actors
    FROM titles t
    LEFT JOIN genres g ON t.id = g.id
    LEFT JOIN directors d ON t.id = d.id
    LEFT JOIN writers w ON t.id = w.id
    LEFT JOIN actors a ON t.id = a.id
    LEFT JOIN rating r ON t.id = r.id
    WHERE t.primaryTitle LIKE :searchQuery
    GROUP BY t.id
    LIMIT :limit OFFSET :offset
");
$stmt->bindValue(':searchQuery', "%$searchQuery%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $recordsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$movies = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
        <a href="Add_Title.php" >Add Entries</a><br>
        <a href="Add_Episode.php" >Add Episode</a><br>
        <a href="Manage_Entries.php" style="background-color: #031020">Modify Entries</a><br>
        <a href="Manage_Episode.php" >Modify Episode</a><br>
        <a href="Manage_reviews.php">Manage Reviews</a><br>
        <a href="Logout.php">Logout</a><br>
    </div>
    <div style="width: 100%">
        <h1>Manage Titles</h1><hr>
        <form action="Manage_Entries.php" method="get" class="search-bar-container" style="width: 50%; margin-left: 450px;">
            <input type="text" name="title" placeholder="Search for a Movie..." class="search-bar" style="width: 100px">
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
        <?php if (isset($edit_movie)) : ?>
            <form method='post' action='' enctype='multipart/form-data' style="background-color: #0f2845; margin-left: 10px; margin-right: 10px; padding-top: 20px; padding-bottom: 20px; border-radius: 5px">
                <label for="poster-upload">
                    <img src="Poster/<?php echo $edit_movie['id']; ?>.jpg" alt="Movie Poster" style="width: 200px; height: 300px; margin-left: 10px; margin-right: 10px; cursor: pointer;">
                </label>
                <input type="file" id="poster-upload" name="poster" style="display: none;">
                <br><br>
                <input type='hidden' name='id' value='<?php echo $edit_movie['id']; ?>'>
                Primary Title:&emsp;<input type='text' name='primaryTitle' value='<?php echo $edit_movie['primaryTitle']; ?>' required>
                &emsp;&emsp;Original Title:&emsp;<input type='text' name='originalTitle' value='<?php echo $edit_movie['originalTitle']; ?>' required><br>
                Type:&emsp;<input type='text' name='type' value='<?php echo $edit_movie['type']; ?>' required><br>
                Release Date:&emsp;<input type='text' name='startYear' value='<?php echo $edit_movie['startYear']; ?>' required>
                &emsp;&emsp;Finish Date:&emsp;<input type='text' name='endYear' value='<?php echo $edit_movie['endYear']; ?>'><br>
                Runtime:&emsp;<input type='text' name='runtime' value='<?php echo $edit_movie['runtime']; ?>' required><br>
                Genre:&emsp;<input type='text' name='genre' value='<?php echo $edit_movie['genres']; ?>' required><br>
                Director:&emsp;<input type='text' name='director' value='<?php echo $edit_movie['directors']; ?>' required>&emsp;&emsp;
                Writer:&emsp;<input type='text' name='writer' value='<?php echo $edit_movie['writers']; ?>' required>&emsp;&emsp;
                Actor:&emsp;<input type='text' name='actor' value='<?php echo $edit_movie['actors']; ?>' required><br>
                Rating:&emsp;<input type='text' name='rating' value='<?php echo $edit_movie['rating']; ?>' required>&emsp;&emsp;
                No of Votes:&emsp;<input type='text' name='noOfVotes' value='<?php echo $edit_movie['noOfVotes']; ?>' required><br><br>
                Plot<br><textarea name='plot' style="width: 50%" required><?php echo $edit_movie['plot']; ?></textarea><br><br>
                <button type='submit' name='update_movie' value='Update' style="width: 200px; background-color: #0b1d35">Update</button>
            </form><br><br><br>
            <table border='1' align='center' style="width: 100%">
                <tr>
                    <th>Title ID</th>
                    <th>Primary Title</th>
                    <th>Original Title</th>
                    <th>Type</th>
                    <th>Release Date</th>
                    <th>Finish Date</th>
                    <th>Runtime</th>
                    <th>Plot</th>
                    <th>Genre</th>
                    <th>Director</th>
                    <th>Writer</th>
                    <th>Actor</th>
                    <th>Rating</th>
                    <th>No of Votes</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($movies as $movie): ?>
                    <tr>
                        <td><?php echo $movie['id']; ?></td>
                        <td><?php echo $movie['primaryTitle']; ?></td>
                        <td><?php echo $movie['originalTitle']; ?></td>
                        <td><?php echo $movie['type']; ?></td>
                        <td><?php echo $movie['startYear']; ?></td>
                        <td><?php echo $movie['endYear']; ?></td>
                        <td><?php echo $movie['runtime']; ?></td>
                        <td><?php echo $movie['plot']; ?></td>
                        <td><?php echo $movie['genres']; ?></td>
                        <td><?php echo $movie['directors']; ?></td>
                        <td><?php echo $movie['writers']; ?></td>
                        <td><?php echo $movie['actors']; ?></td>
                        <td><?php echo $movie['rating']; ?></td>
                        <td><?php echo $movie['noOfVotes']; ?></td>
                        <td>
                            <a href='Manage_Entries.php?edit_id=<?php echo $movie['id']; ?>'><button>Edit</button></a>
                            <a href='Delete_movie.php?id=<?php echo $movie['id']; ?>'><button>Delete</button></a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>
            <?php pagination($currentPage, $totalPages, $searchQuery);
            ?>
        <?php else : ?>
            <table border='1' align='center' style="width: 100%">
                <tr>
                    <th>Title ID</th>
                    <th>Primary Title</th>
                    <th>Original Title</th>
                    <th>Type</th>
                    <th>Release Date</th>
                    <th>Finish Date</th>
                    <th>Runtime</th>
                    <th>Plot</th>
                    <th>Genre</th>
                    <th>Director</th>
                    <th>Writer</th>
                    <th>Actor</th>
                    <th>Rating</th>
                    <th>No of Votes</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($movies as $movie): ?>
                    <tr>
                        <td><?php echo $movie['id']; ?></td>
                        <td><?php echo $movie['primaryTitle']; ?></td>
                        <td><?php echo $movie['originalTitle']; ?></td>
                        <td><?php echo $movie['type']; ?></td>
                        <td><?php echo $movie['startYear']; ?></td>
                        <td><?php echo $movie['endYear']; ?></td>
                        <td><?php echo $movie['runtime']; ?></td>
                        <td><?php echo $movie['plot']; ?></td>
                        <td><?php echo $movie['genres']; ?></td>
                        <td><?php echo $movie['directors']; ?></td>
                        <td><?php echo $movie['writers']; ?></td>
                        <td><?php echo $movie['actors']; ?></td>
                        <td><?php echo $movie['rating']; ?></td>
                        <td><?php echo $movie['noOfVotes']; ?></td>
                        <td>
                            <a href='Manage_Entries.php?edit_id=<?php echo $movie['id']; ?>'><button>Edit</button></a>
                            <a href='Delete_movie.php?id=<?php echo $movie['id']; ?>'><button>Delete</button></a>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>
            <?php pagination($currentPage, $totalPages, $searchQuery);
            ?>
        <?php endif; ?>

    </div>
</div>
</body>
</html>

<?php
// Pagination function
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

