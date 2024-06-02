<?php
session_start();
include 'Admin_Logged.php';
include 'db_connect.php';

if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM userinfo WHERE username = :username");
    $stmt->execute(['username' => $edit_id]);
    $edit_user = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $dateOfBirth = $_POST['dateOfBirth'];

    $stmt = $conn->prepare("UPDATE userinfo SET mail = :email, gender = :gender, dateOfBirth = :dateOfBirth WHERE username = :username");
    $stmt->execute(['email' => $email, 'gender' => $gender, 'dateOfBirth' => $dateOfBirth, 'username' => $username]);

    header("Location: Manage_Users.php?sux=User updated successfully!");
    exit();
}

// Fetch all users
$stmt = $conn->query("SELECT * FROM userinfo");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>User Management - IMRS</title>
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
        <a style="margin-top: 50px" href="Admin_Dashboard.php">Admin Dashboard</a><br>
        <a href="Manage_Users.php" style="background-color: #031020">Manage Users</a><br>
        <a href="Add_Title.php" >Add Entries</a><br>
        <a href="Add_Episode.php" >Add Episode</a><br>
        <a href="Manage_Entries.php">Modify Entries</a><br>
        <a href="Manage_Episode.php" >Modify Episode</a><br>
        <a href="Manage_reviews.php">Manage Reviews</a><br>
        <a href="Logout.php">Logout</a><br>
    </div>
    <div style="width: 100%">
<h1>Manage Users</h1><hr>

<?php if (isset($edit_user)) :
    echo "<table border='1' align='center'>";
    echo "<tr>";
    echo "<th>Username</th>";
    echo "<th>Email</th>";
    echo "<th>Gender</th>";
    echo "<th>Date of Birth</th>";
    echo "<th>Registered Date</th>";
    echo "<th>Action</th>";
    echo "</tr>";

    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['mail'] . "</td>";
        echo "<td>" . $user['gender'] . "</td>";
        echo "<td>" . $user['dateOfBirth'] . "</td>";
        echo "<td>" . $user['regDate'] . "</td>";
        echo "<td>";
        echo "<a href='Manage_Users.php?edit_id=" . $user['username'] . "'><button style='margin-right: 5px'>Edit</button></a>";
        echo "<a href='Delete_User.php?id=" . $user['username'] . "'><button>Delete</button></a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<br><br><br><form method='post' action=''>";
    echo "<input type='hidden' name='username' value='" . $edit_user['username'] . "'>";
    echo "Email: <input type='email' name='email' value='" . $edit_user['mail'] . "' style='margin-left: 10px' required><br>";
    echo "Gender: <select name='gender' style='margin-right: 14px; padding-left: 8px; margin-left: 10px; margin-bottom: 15px; width: 313px; height: 33px; border-radius: 3px' required>";
    echo "<option value='Male' " . ($edit_user['gender'] == 'Male' ? 'selected' : '') . ">Male</option>";
    echo "<option value='Female' " . ($edit_user['gender'] == 'Female' ? 'selected' : '') . ">Female</option>";
    echo "<option value='Other' " . ($edit_user['gender'] == 'Other' ? 'selected' : '') . ">Other</option>";
    echo "</select><br>";
    echo "Date of Birth: <input type='date' name='dateOfBirth' value='" . $edit_user['dateOfBirth'] . "' style='margin-right: 59px; margin-left: 10px' required><br>";
    echo "<button type='submit' name='update_user' value='Update'>Update</button>";
    echo "</form>";

else :
    echo "<table border='1' align='center'>";
    echo "<tr>";
    echo "<th>Username</th>";
    echo "<th>Email</th>";
    echo "<th>Gender</th>";
    echo "<th>Date of Birth</th>";
    echo "<th>Registered Date</th>";
    echo "<th>Action</th>";
    echo "</tr>";

    foreach ($users as $user) {
        echo "<tr>";
        echo "<td>" . $user['username'] . "</td>";
        echo "<td>" . $user['mail'] . "</td>";
        echo "<td>" . $user['gender'] . "</td>";
        echo "<td>" . $user['dateOfBirth'] . "</td>";
        echo "<td>" . $user['regDate'] . "</td>";
        echo "<td>";
        echo "<a href='Manage_Users.php?edit_id=" . $user['username'] . "'><button>Edit</button></a>";
        echo "<a href='Delete_User.php?id=" . $user['username'] . "'><button>Delete</button></a>";
        echo "</td>";
        echo "</tr>";
    }

    echo "</table>";
    endif;
?>

<br><div id="success" class="success">
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
</div>
</body>
</html>
