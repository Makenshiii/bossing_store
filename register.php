<?php
include 'config.php';
include 'auth.php';

$message = "";
$error = "";

// Default role
$role = "Staff";

// If Admin is logged in, allow choosing role
if (isLoggedIn() && isAdmin() && isset($_POST['role'])) {
    $role = $_POST['role'];
}

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

            $insert = $conn->prepare("
                INSERT INTO users
                (username, email, password, role)
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

                if (isLoggedIn() && isAdmin()) {

                    header("Location: users.php");

                } else {

                    header("Location: login.php");

                }

                exit();

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

<?php
if (isLoggedIn() && isAdmin()) {
    include 'navbar.php';
    echo '<div class="main-content">';
}
?>

<div class="login-container">

    <h2>Bossing Store</h2>

    <?php if(isLoggedIn() && isAdmin()){ ?>

        <h3>Add New User</h3>

    <?php } else { ?>

        <h3>Create Staff Account</h3>

    <?php } ?>

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

        <?php if(isLoggedIn() && isAdmin()){ ?>

        <label>Role</label>

        <select name="role">

            <option value="Staff">Staff</option>

            <option value="Admin">Admin</option>

        </select>

        <?php } ?>

        <br><br>

        <button
        type="submit"
        name="register">

        <?php

        if(isLoggedIn() && isAdmin())
        {
            echo "Create User";
        }
        else
        {
            echo "Register";
        }

        ?>

        </button>

    </form>

    <br>

    <?php if(isLoggedIn() && isAdmin()){ ?>

        <a href="users.php">

        ← Back to Users

        </a>

    <?php } else { ?>

        <a href="login.php">

        Already have an account? Login Here

        </a>

    <?php } ?>

</div>

<?php
if (isLoggedIn() && isAdmin()) {
    echo "</div>";
}
?>

</body>

</html>
