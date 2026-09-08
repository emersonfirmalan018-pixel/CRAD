<?php

require_once 'db.php';

// clear any leftover OTP pending data (in case OTP flow was previously used)
unset(
    $_SESSION['pending_user_id'],
    $_SESSION['pending_username'],
    $_SESSION['otp'],
    $_SESSION['otp_expires']
);

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare(
        "SELECT * FROM users WHERE username = ?"
    );

    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'] ?? $user['full_name'] ?? '';

        // Show loading animation page, then redirect to dashboard
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Signing in...</title>
            <link rel="stylesheet" href="assets/style.css">
        </head>
        <body>

        <div class="overlay" id="loadingOverlay">
            <div class="loader">
                <img class="loader-gif" src="assets/blue.gif" alt="loading">
            </div>
        </div>

        <script src="assets/app.js"></script>
        <script>
            // show animation then redirect to dashboard (longer)
            showLoadingAnimation(2000, 'Signing you in...', 'dashboard.php');
        </script>

        </body>
        </html>
        <?php
        exit;

    } else {

        $error = "Invalid username or password.";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Student Management System</title>
<link rel="icon" type="image/png" href="logo.png">

<link rel="stylesheet"
href="assets/style.css">

</head>

<body class="login-page">

<div class="login-container">

    <!-- LEFT SIDE -->

    <div class="login-left">

      <img src="logo.png" alt="CRAD Logo" class="login-logo">

        <h1>Sign in</h1>

        <?php if ($error): ?>

            <div class="error-message">
                <?= e($error) ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <label>
                Username *
            </label>

            <input
                type="text"
                name="username"
                placeholder="Username"
                required
            >

            <label>
                Password *
            </label>

            <div class="password-box">

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    required
                >

                <button
                    type="button"
                    onclick="togglePassword()"
                >
                    👁
                </button>

            </div>

            <button
                type="submit"
                class="login-button"
            >
                Sign in
            </button>

        </form>

    </div>


    <!-- RIGHT SIDE -->

    <div class="login-right">

        <div class="circle circle1"></div>
        <div class="circle circle2"></div>
        <div class="circle circle3"></div>

        <div class="login-hero">

            <h2>
                Criteria<br> 
                Review and<br> 
                Approach Document
                
                
            </h2>



        </div>

    </div>

</div>

<script src="assets/app.js"></script>

</body>
</html>