<?php
include 'config.php';
include 'auth.php';

$message = "";
$error = "";

if (isset($_POST['register'])) {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (
        empty($username) ||
        empty($email) ||
        empty($password) ||
        empty($confirm)
    ) {

        $error = "Please fill in all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Invalid email address.";

    } elseif (strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

    } elseif ($password != $confirm) {

        $error = "Passwords do not match.";

    } else {

        $check = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
        $check->bind_param("ss", $username, $email);
        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $error = "Username or Email already exists.";

        } else {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Automatically assign Staff role
            $role = "Staff";

            $insert = $conn->prepare("
                INSERT INTO users (username, email, password, role)
                VALUES (?, ?, ?, ?)
            ");

            $insert->bind_param(
                "ssss",
                $username,
                $email,
                $hashedPassword,
                $role
            );

            if ($insert->execute()) {

                $message = "Registration Successful! You can now login.";

            } else {

                $error = "Registration Failed.";

            }

        }

    }

}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Register | Bossing Store</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="login-container">

    <h2>Bossing Store</h2>

    <h3>Create Staff Account</h3>

    <?php

    if($message!="")
    {
        echo "<div class='success'>$message</div>";
    }

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
        required>

        <label>Email</label>

        <input
        type="email"
        name="email"
        required>

        <label>Password</label>

        <input
        type="password"
        name="password"
        required>

        <label>Confirm Password</label>

        <input
        type="password"
        name="confirm_password"
        required>

        <br><br>

        <button
        type="submit"
        name="register">

        Register

        </button>

    </form>

    <br>

    <p>

    Already have an account?

    <a href="login.php">

    Login Here

    </a>

    </p>

</div>

</body>

</html>