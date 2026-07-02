<?php
include_once 'auth.php';

requireLogin();
?>

<div class="sidebar">

    <div class="logo">
        <h2>Bossing Store</h2>
    </div>

    <div class="user-info">

        <p><strong><?php echo $_SESSION['username']; ?></strong></p>

        <small><?php echo $_SESSION['role']; ?></small>

    </div>

    <ul>

        <li>
            <a href="dashboard.php">🏠 Dashboard</a>
        </li>

        <li>
            <a href="products.php">📦 Products</a>
        </li>

        <li>
            <a href="categories.php">📂 Categories</a>
        </li>

        <li>
            <a href="sales.php">💰 Sales</a>
        </li>

        <?php if ($_SESSION['role'] == "Admin") { ?>

        <li>
            <a href="users.php">👥 Users</a>
        </li>

        <?php } ?>

        <li>
            <a href="logout.php">🚪 Logout</a>
        </li>

    </ul>

</div>