<?php
include 'config.php';
include 'auth.php';

requireAdmin();

$message = "";
$error = "";

$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY category_name ASC");

if (isset($_POST['save'])) {

    $category = $_POST['category'];
    $product = trim($_POST['product']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);

    if (
        empty($category) ||
        empty($product) ||
        empty($price) ||
        empty($stock)
    ) {

        $error = "Please fill in all fields.";

    } elseif (!is_numeric($price) || !is_numeric($stock)) {

        $error = "Price and Stock must be numeric.";

    } else {

        $stmt = $conn->prepare("
            INSERT INTO products
            (category_id, product_name, price, stock)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isdi",
            $category,
            $product,
            $price,
            $stock
        );

        if ($stmt->execute()) {

            header("Location: products.php");
            exit();

        } else {

            $error = "Failed to save product.";

        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Product</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="main-content">

<h1>Add Product</h1>

<br>

<?php

if($error!="")
{
    echo "<div class='error'>$error</div>";
}

?>

<div class="form-container">

<form method="POST">

<label>Category</label>

<select name="category" required>

<option value="">Select Category</option>

<?php

while($row=mysqli_fetch_assoc($categories))
{

?>

<option value="<?php echo $row['id']; ?>">

<?php echo $row['category_name']; ?>

</option>

<?php

}

?>

</select>

<br><br>

<label>Product Name</label>

<input
type="text"
name="product"
required>

<br>

<label>Price</label>

<input
type="number"
step="0.01"
name="price"
required>

<br>

<label>Stock</label>

<input
type="number"
name="stock"
required>

<br><br>

<button
type="submit"
name="save">

Save Product

</button>

<a href="products.php" class="button">

Cancel

</a>

</form>

</div>

</div>

</body>

</html>