<?php
include 'User_Logged.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Style.css">
    <title>Advanced Search - IMRS</title>
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

<?php
include 'Functions.php';
include 'db_connect.php';

try {
    $search_query = $_GET['title'] ?? '';
    $entriesPerPage = 30;
    $currentPage = $_GET['page'] ?? 1;
    $currentPage = max(1, (int)$currentPage);
    $release_year1 = $_GET['release_year1'] ?? null;
    $release_year2 = $_GET['release_year2'] ?? null;
    $rating_range1 = $_GET['rating_range1'] ?? null;
    $rating_range2 = $_GET['rating_range2'] ?? null;
    $votes_range1 = $_GET['votes_range1'] ?? null;
    $votes_range2 = $_GET['votes_range2'] ?? null;
    $runtime_range1 = $_GET['runtime_range1'] ?? null;
    $runtime_range2 = $_GET['runtime_range2'] ?? null;
    $sql = "SELECT COUNT(*) FROM titles NATURAL JOIN rating WHERE 1=1";

    if (!empty($search_query)) {
        $sql .= " AND (primaryTitle LIKE :search_query OR originalTitle LIKE :search_query)";
    }
    if (!empty($_GET['types'])) {
        $types = array_map('htmlspecialchars', $_GET['types']);
        $types_string = implode("','", $types);
        $sql .= " AND type IN ('$types_string')";
    }
    if (!empty($release_year1) && !empty($release_year2)) {
        $sql .= " AND (startYear BETWEEN :release_year1 AND :release_year2)";
    }
    if (!empty($rating_range1) && !empty($rating_range2)) {
        $sql .= " AND (rating BETWEEN :rating_range1 AND :rating_range2)";
    }
    if (!empty($votes_range1) && !empty($votes_range2)) {
        $sql .= " AND (noOfVotes BETWEEN :votes_range1 AND :votes_range2)";
    }
    if (!empty($runtime_range1) && !empty($runtime_range2)) {
        $sql .= " AND (runtime BETWEEN :runtime_range1 AND :runtime_range2)";
    }

    $selectedOption = $_GET['sort'] ?? 'noOfVotes';
    $sort_order = $_GET['sort-order'] ?? 'desc';

    $stmt = $conn->prepare($sql);
    if (!empty($search_query)) {
        $stmt->bindValue(':search_query', '%' . $search_query . '%');
    }
    if (!empty($release_year1) && !empty($release_year2)) {
        $stmt->bindParam(':release_year1', $release_year1, PDO::PARAM_INT);
        $stmt->bindParam(':release_year2', $release_year2, PDO::PARAM_INT);
    }
    if (!empty($rating_range1) && !empty($rating_range2)) {
        $stmt->bindParam(':rating_range1', $rating_range1, PDO::PARAM_INT);
        $stmt->bindParam(':rating_range2', $rating_range2, PDO::PARAM_INT);
    }
    if (!empty($votes_range1) && !empty($votes_range2)) {
        $stmt->bindParam(':votes_range1', $votes_range1, PDO::PARAM_INT);
        $stmt->bindParam(':votes_range2', $votes_range2, PDO::PARAM_INT);
    }
    if (!empty($runtime_range1) && !empty($runtime_range2)) {
        $stmt->bindParam(':runtime_range1', $runtime_range1, PDO::PARAM_INT);
        $stmt->bindParam(':runtime_range2', $runtime_range2, PDO::PARAM_INT);
    }
    $stmt->execute();
    $totalRows = $stmt->fetchColumn();

    $totalPages = ceil($totalRows / $entriesPerPage);

    $startIndex = ($currentPage - 1) * $entriesPerPage;

    $sql = "SELECT titles.id, type, primaryTitle, originalTitle, startYear, endYear, runtime, rating, noOfVotes 
            FROM titles NATURAL JOIN rating WHERE 1=1";

    if (!empty($search_query)) {
        $sql .= " AND (primaryTitle LIKE :search_query OR originalTitle LIKE :search_query)";
    }
    if (!empty($_GET['types'])) {
        $types = array_map('htmlspecialchars', $_GET['types']);
        $types_string = implode("','", $types);
        $sql .= " AND type IN ('$types_string')";
    }
    if (!empty($release_year1) && !empty($release_year2)) {
        $sql .= " AND (startYear BETWEEN :release_year1 AND :release_year2)";
    }
    if (!empty($rating_range1) && !empty($rating_range2)) {
        $sql .= " AND (rating BETWEEN :rating_range1 AND :rating_range2)";
    }
    if (!empty($votes_range1) && !empty($votes_range2)) {
        $sql .= " AND (noOfVotes BETWEEN :votes_range1 AND :votes_range2)";
    }
    if (!empty($runtime_range1) && !empty($runtime_range2)) {
        $sql .= " AND (runtime BETWEEN :runtime_range1 AND :runtime_range2)";
    }

    $sql .= " ORDER BY " . $selectedOption . " " . $sort_order;
    $sql .= " LIMIT $startIndex, $entriesPerPage";

    $stmt = $conn->prepare($sql);
    if (!empty($search_query)) {
        $stmt->bindValue(':search_query', '%' . $search_query . '%');
    }
    if (!empty($release_year1) && !empty($release_year2)) {
        $stmt->bindParam(':release_year1', $release_year1, PDO::PARAM_INT);
        $stmt->bindParam(':release_year2', $release_year2, PDO::PARAM_INT);
    }
    if (!empty($rating_range1) && !empty($rating_range2)) {
        $stmt->bindParam(':rating_range1', $rating_range1, PDO::PARAM_INT);
        $stmt->bindParam(':rating_range2', $rating_range2, PDO::PARAM_INT);
    }
    if (!empty($votes_range1) && !empty($votes_range2)) {
        $stmt->bindParam(':votes_range1', $votes_range1, PDO::PARAM_INT);
        $stmt->bindParam(':votes_range2', $votes_range2, PDO::PARAM_INT);
    }
    if (!empty($runtime_range1) && !empty($runtime_range2)) {
        $stmt->bindParam(':runtime_range1', $runtime_range1, PDO::PARAM_INT);
        $stmt->bindParam(':runtime_range2', $runtime_range2, PDO::PARAM_INT);
    }
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo "<div style='display: flex'>";
        Search_Output($stmt);
        Filtered_Output();
        echo "</div>";

        pagination($currentPage, $totalPages);
    } else {
        header("Location: Advanced_Search.php?errorS=No records found");
        exit();
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

</body>
</html>