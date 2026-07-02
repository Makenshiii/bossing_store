<?php
// ======================================
// BOSSING STORE MANAGEMENT SYSTEM
// auth.php
// ======================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Check if user is logged in
|--------------------------------------------------------------------------
*/
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/*
|--------------------------------------------------------------------------
| Require Login
|--------------------------------------------------------------------------
*/
function requireLogin()
{
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

/*
|--------------------------------------------------------------------------
| Check Admin
|--------------------------------------------------------------------------
*/
function isAdmin()
{
    return isset($_SESSION['role']) && $_SESSION['role'] === "Admin";
}

/*
|--------------------------------------------------------------------------
| Require Admin
|--------------------------------------------------------------------------
*/
function requireAdmin()
{
    requireLogin();

    if (!isAdmin()) {
        echo "<script>
                alert('Access Denied! Admin Only.');
                window.location='dashboard.php';
              </script>";
        exit();
    }
}

/*
|--------------------------------------------------------------------------
| Get Logged-in User
|--------------------------------------------------------------------------
*/
function getUsername()
{
    return $_SESSION['username'] ?? "";
}

function getRole()
{
    return $_SESSION['role'] ?? "";
}

function getUserID()
{
    return $_SESSION['user_id'] ?? 0;
}
?>