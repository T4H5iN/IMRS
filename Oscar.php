<?php
include 'User_Logged.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Style.css">
    <title>Oscar Winner - IMRS</title>
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
include 'Functions.php';
include 'db_connect.php';
$type = $_GET['type'];

$entriesPerPage = 25;

try {
    if($type == 'a') {
        $totalPages = ceil($conn->query("SELECT COUNT(*) FROM titles NATURAL JOIN rating WHERE oscar=2")->fetchColumn() / $entriesPerPage);

        $currentPage = $_GET['page'] ?? 1;

        $startIndex = ($currentPage - 1) * $entriesPerPage;

        $sql = "SELECT * FROM titles NATURAL JOIN rating WHERE oscar=2 order by startYear desc LIMIT $startIndex, $entriesPerPage";

        $result = $conn->query($sql);

        if ($result->rowCount() > 0) {
            echo "<div style='display: flex'>";
            Search_Output($result);
            Filtered_Output();
            echo "</div>";
            pagination($currentPage, $totalPages);
        } else {
            echo "No records found";
        }
    }
    if($type == 'b') {
        $totalPages = ceil($conn->query("SELECT COUNT(*) FROM titles NATURAL JOIN rating WHERE oscar=1")->fetchColumn() / $entriesPerPage);

        $currentPage = $_GET['page'] ?? 1;

        $startIndex = ($currentPage - 1) * $entriesPerPage;

        $sql = "SELECT * FROM titles NATURAL JOIN rating WHERE oscar=1 order by startYear desc LIMIT $startIndex, $entriesPerPage";

        $result = $conn->query($sql);

        if ($result->rowCount() > 0) {
            echo "<div style='display: flex'>";
            Search_Output($result);
            echo "</div>";
            pagination($currentPage, $totalPages);
        } else {
            echo "No records found";
        }
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


</body>
</html>
