<?php
include 'config.php';
include 'auth.php';

if (isLoggedIn()) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if (isset($_POST['login'])) {

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter your username and password.";
    } else {

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                header("Location: dashboard.php");
                exit();

            } else {
                $error = "Incorrect password.";
            }

        } else {
            $error = "Username not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Bossing Store | Login</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="login-container">

    <h2>Bossing Store</h2>

    <h3>Login</h3>

    <?php

    if($error!="")
    {
        echo "<div class='error'>$error</div>";
    }

    ?>

    <form method="POST">

        <label>Username</label>

        <input
        type="text"
        name="username"
        placeholder="Enter Username"
        required>

        <label>Password</label>

        <input
        type="password"
        name="password"
        placeholder="Enter Password"
        required>

        <button
        type="submit"
        name="login">

        Login

        </button>

    </form>

    <p>

        Don't have an account?

        <a href="register.php">

        Register Here

        </a>

    </p>

</div>

</body>

</html>