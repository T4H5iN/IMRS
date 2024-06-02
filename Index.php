<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="Style.css">
    <style>
        body {
            margin-right: 15%;
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
            width: 300px;
            height: 350px;
            text-align: start;
            margin-top: 55%;
        }

        hr {
            background-image: linear-gradient(to right, rgb(23, 43, 69), rgb(202, 214, 241), rgb(23, 43, 69));
        }

        .r-container {
            background-color: #172b45;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 10px 30px 60px;
            width: 300px;
            height: 800px;
            text-align: start;
        }

        .index-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40%;
        }
    </style>
</head>
<body>
<div class="index-container">
<div class="container">
    <h2 align="center">Login</h2><hr>
    <form action="Login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <div class="password-container">
            <input type="password" id="password" name="password" class="password-input" required>
            <img src="Illustrations/hp.png" alt="Show/Hide Password" class="password-toggle" onclick="togglePassword('password', '.password-toggle')">
        </div>
        <a href="Password_Recovery.php"><p align="right" style="font-size: 12px; margin-top: 0; cursor: pointer">Forgot Password?</p></a>

        <div id="error-message" class="error-message">
            <?php
            if (isset($_GET['error'])) {
                echo htmlspecialchars($_GET['error']);
            }
            ?>
        </div>

        <button type="submit">Login</button>
    </form>
</div>
<div class="r-container">
    <h2 align="center">Register</h2><hr>
    <form action="Register.php" method="post">
        <!--<form action="send_email.php" method="post">-->
            <div style="display: flex; align-items: baseline">
                <label for="email">E-mail:</label>
                <!--<button type="submit" class="verify">Verify Email</button>-->
            </div>
            <input type="email" id="email" name="email" required>
        <!--</form>-->

        <div id="error-mail" class="error-message">
            <?php
            if (isset($_GET['errorM'])) {
                echo htmlspecialchars($_GET['errorM']);
            }
            ?>
        </div>
        <!--
        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" required minlength="5" maxlength="50">
        -->

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required minlength="3" maxlength="20">

        <div id="error-username" class="error-message">
            <?php
            if (isset($_GET['errorU'])) {
                echo htmlspecialchars($_GET['errorU']);
            }
            ?>
        </div>

        <div style="display: flex; align-items: center">
            <label for="password1">Password: </label>
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
        
        <div id="error-password" class="error-message">
            <?php
            if (isset($_GET['errorP'])) {
                echo htmlspecialchars($_GET['errorP']);
            }
            ?>
        </div>
        
        <label for="gender">Gender:</label>
        <div class="gender">
            <input type="radio" id="Male" name="gender" value="Male" required><label for="Male">Male</label>
            <input type="radio" id="Female" name="gender" value="Female" required><label for="Female">Female</label>
            <input type="radio" id="Other" name="gender" value="Other" required><label for="Other">Other</label>
        </div>


        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="2015-12-31" min="1950-01-01" max="2015-12-31" />

        <label for="sq">Security Question:</label>
        <input type="text" id="sq" name="sq" placeholder="Write your nickname" required minlength="3" maxlength="20">


        <div id="success" class="success">
            <?php
                if (isset($_GET['success'])) {
                    echo htmlspecialchars($_GET['success']);
                }
            ?>
        </div>

        <button type="submit">Register</button>
    </form>
</div>
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
