<?php
include 'Admin_Logged.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="Style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Admin Dashboard - IMRS</title>
</head>
<body>
<div class="header">
    <a href="Home.php"><img src="Illustrations/imrs.png" alt="Logo" class="logo"></a>
    <div class="dropP">
        <?php
        session_start();
        include 'db_connect.php';
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
        <a style="margin-top: 50px;background-color: #031020" href="Admin_Dashboard.php">Admin Dashboard</a><br>
        <a href="Manage_Users.php">Manage Users</a><br>
        <a href="Add_Title.php" >Add Entries</a><br>
        <a href="Add_Episode.php" >Add Episode</a><br>
        <a href="Manage_Entries.php">Modify Entries</a><br>
        <a href="Manage_Episode.php" >Modify Episode</a><br>
        <a href="Manage_reviews.php">Manage Reviews</a><br>
        <a href="Logout.php">Logout</a><br>
    </div>
    <div style="width: 100%">
        <h1>Welcome <?php echo $_SESSION['username'];?> </h1>
        <?php
        include 'db_connect.php';
        $query1 = "SELECT COUNT(*) as userCount FROM userinfo";
        $query2 = "SELECT COUNT(*) as movieCount FROM titles where type='movie'";
        $query3 = "SELECT COUNT(*) as showCount FROM titles where type like '%series%'";
        $query4 = "SELECT COUNT(*) as epCount FROM episodes";
        $query5 = "SELECT COUNT(*) as tvCount FROM titles where type='tvSeries'";
        $query6 = "SELECT COUNT(*) as miniCount FROM titles where type='tvMiniSeries'";
        $stmt1 = $conn->prepare($query1);
        $stmt2 = $conn->prepare($query2);
        $stmt3 = $conn->prepare($query3);
        $stmt4 = $conn->prepare($query4);
        $stmt5 = $conn->prepare($query5);
        $stmt6 = $conn->prepare($query6);
        $stmt1->execute();
        $stmt2->execute();
        $stmt3->execute();
        $stmt4->execute();
        $stmt5->execute();
        $stmt6->execute();
        $userCount = $stmt1->fetchColumn();
        $movieCount = $stmt2->fetchColumn();
        $showCount = $stmt3->fetchColumn();
        $epCount = $stmt4->fetchColumn();
        $tvCount = $stmt5->fetchColumn();
        $miniCount = $stmt6->fetchColumn();
        ?>
        <div class="stats-counter" style="background-color: #0f2845">
            <span><?php echo $userCount; ?><br>Registered User</span>
            <span><?php echo $movieCount; ?><br>Registered Movies</span>
            <span><?php echo $showCount; ?><br>Registered Shows</span>
            <span><?php echo $epCount; ?><br>Registered Episodes</span>
        </div>
        <canvas id="doughnutChart" style="max-width: 400px; max-height: 400px;"></canvas>
    </div>
</div>

<script>
    const ctx = document.getElementById('doughnutChart').getContext('2d');
    const data = {
        labels: ['Movies', 'Tv Series', 'Tv Mini Series'],
        datasets: [{
            data: [<?php echo $movieCount; ?>, <?php echo $tvCount; ?>, <?php echo $miniCount; ?>],
            backgroundColor: [
                '#163963',
                '#102949',
                '#0A1A2E'
            ],
            borderColor: [
                '#cad6f1'
            ],
            borderWidth: 1
        }]
    };
    const config = {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'Registered Title',
                    font: {
                        size: 16
                    },
                    color: '#CAD6F1'
                }
            }
        },
    };

    const doughnutChart = new Chart(ctx, config);
</script>
</body>
</html>
