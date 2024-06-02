<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="Style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #172b45;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10%;
            padding-top: 30px;
            padding-right: 30px;
            padding-left: 30px;
            width: 300px;
            height: 400px;
            text-align: start;
            margin-bottom: 50px;
        }

        hr {
            background-image: linear-gradient(to right, rgb(23, 43, 69), rgb(202, 214, 241), rgb(23, 43, 69));
        }

    </style>
</head>
<body>
    <div class="container">
        <a href="Index.php"><p align="left" style="font-size: 12px; margin-top: 0; cursor: pointer">Back to Login page</p></a>
        <h2 align="center">Password Reset</h2><hr>
        <form action="Reset.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <div style="display: flex; align-items: center">
                <label for="password1">New Password: </label>
                <div class="password-requirements-icon">
                    <img src="Illustrations/info.png" alt="info">
                    <span class="password-requirements-tooltip">
                    Password must contain at least<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;- 8 characters<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;- one number<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;- one special character<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;- one lowercase letter<br>
                    &nbsp;&nbsp;&nbsp;&nbsp;- one uppercase letter
                </span>
                </div>
            </div>
            <div class="password-container">
                <input type="password" id="password1" name="password1" class="password-input" required>
                <img src="Illustrations/hp.png" alt="Show/Hide Password" class="password-toggle1" onclick="togglePassword1()">
            </div>

            <div id="error-password-requirement" class="error-message">
                <?php
                if (isset($_GET['errorPR'])) {
                    echo htmlspecialchars($_GET['errorPR']);
                }
                ?>
            </div>

            <label for="confirm">Confirm Password:</label>
            <div class="password-container">
                <input type="password" id="confirm" name="confirm" class="password-input" required>
                <img src="Illustrations/hp.png" alt="Show/Hide Password" class="password-toggle2" onclick="togglePassword2()">
            </div>

            <label for="sq">Security Question:</label>
            <input type="text" id="sq" name="sq" placeholder="Write your nickname" required minlength="3" maxlength="20">

            <div id="error-password" class="error-message">
                <?php
                if (isset($_GET['errorP'])) {
                    echo htmlspecialchars($_GET['errorP']);
                }
                ?>
            </div>

            <button type="submit">Reset</button>
        </form>
    </div>

<script>
    function togglePassword(passwordFieldId, iconId) {
        let passwordField = document.getElementById(passwordFieldId);
        let icon = document.querySelector(iconId);

        if (passwordField.type === "password") {
            passwordField.type = "text";
            icon.src = "Illustrations/sp.png";
        } else {
            passwordField.type = "password";
            icon.src = "Illustrations/hp.png";
        }
    }

    function togglePassword1() {
        togglePassword("password1", ".password-toggle1");
    }

    function togglePassword2() {
        togglePassword("confirm", ".password-toggle2");
    }
</script>
</body>
</html>
