<?php
include 'auth.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

header("Location: login.php");
exit();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bossing Store Management System</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="welcome-box">

    <h1>Bossing Store Management System</h1>

    <p>
        Welcome to the inventory and sales management system.
    </p>

    <a href="login.php" class="btn">
        Login
    </a>

    <a href="register.php" class="btn">
        Register
    </a>

</div>

</body>
</html>