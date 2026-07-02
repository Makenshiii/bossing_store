<?php
include 'config.php';
include 'auth.php';

requireLogin();

// Total Products
$product = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$productCount = mysqli_fetch_assoc($product)['total'];

// Total Categories
$category = mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories");
$categoryCount = mysqli_fetch_assoc($category)['total'];

// Total Sales
$sales = mysqli_query($conn, "SELECT COUNT(*) AS total FROM sales");
$salesCount = mysqli_fetch_assoc($sales)['total'];

// Total Users
$user = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$userCount = mysqli_fetch_assoc($user)['total'];
?>

<!DOCTYPE html>
<html>

<head>

    <title>Dashboard | Bossing Store</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="main-content">

    <h1>Dashboard</h1>

    <p>
        Welcome,
        <strong><?php echo getUsername(); ?></strong>
        (<?php echo getRole(); ?>)
    </p>

    <div class="cards">

        <div class="card">

            <h3>Total Products</h3>

            <h2><?php echo $productCount; ?></h2>

        </div>

        <div class="card">

            <h3>Total Categories</h3>

            <h2><?php echo $categoryCount; ?></h2>

        </div>

        <div class="card">

            <h3>Total Sales</h3>

            <h2><?php echo $salesCount; ?></h2>

        </div>

        <?php if(isAdmin()) { ?>

        <div class="card">

            <h3>Total Users</h3>

            <h2><?php echo $userCount; ?></h2>

        </div>

        <?php } ?>

    </div>

    <br>

    <h2>Quick Menu</h2>

    <div class="quick-links">

        <a href="products.php" class="button">
            Manage Products
        </a>

        <a href="categories.php" class="button">
            Categories
        </a>

        <a href="sales.php" class="button">
            Sales
        </a>

        <?php if(isAdmin()) { ?>

        <a href="users.php" class="button">
            Users
        </a>

        <?php } ?>

    </div>

    <br><br>

    <h2>Recent Sales</h2>

    <table>

        <tr>

            <th>ID</th>

            <th>Product</th>

            <th>Quantity</th>

            <th>Total</th>

            <th>Date</th>

        </tr>

        <?php

        $recent = mysqli_query($conn,"
            SELECT sales.*,
            products.product_name
            FROM sales
            INNER JOIN products
            ON sales.product_id = products.id
            ORDER BY sale_date DESC
            LIMIT 5
        ");

        while($row = mysqli_fetch_assoc($recent))
        {

        ?>

        <tr>

            <td><?php echo $row['id']; ?></td>

            <td><?php echo $row['product_name']; ?></td>

            <td><?php echo $row['quantity']; ?></td>

            <td>₱<?php echo number_format($row['total_price'],2); ?></td>

            <td><?php echo $row['sale_date']; ?></td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>

</html>