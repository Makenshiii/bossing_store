<?php
include 'config.php';
include 'auth.php';

requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = intval($_GET['id']);

$productQuery = $conn->prepare("
SELECT *
FROM products
WHERE id = ?
");

$productQuery->bind_param("i", $id);
$productQuery->execute();

$product = $productQuery->get_result()->fetch_assoc();

if (!$product) {
    header("Location: products.php");
    exit();
}

$categories = mysqli_query($conn, "
SELECT *
FROM categories
ORDER BY category_name ASC
");

$error = "";

if (isset($_POST['update'])) {

    $category = $_POST['category'];
    $productName = trim($_POST['product']);
    $price = trim($_POST['price']);
    $stock = trim($_POST['stock']);

    if (
        empty($category) ||
        empty($productName) ||
        empty($price) ||
        empty($stock)
    ) {

        $error = "Please complete all fields.";

    } else {

        $update = $conn->prepare("
        UPDATE products
        SET
        category_id=?,
        product_name=?,
        price=?,
        stock=?
        WHERE id=?
        ");

        $update->bind_param(
            "isdii",
            $category,
            $productName,
            $price,
            $stock,
            $id
        );

        if ($update->execute()) {

            header("Location: products.php");
            exit();

        } else {

            $error = "Unable to update product.";

        }

    }

}
?>

<!DOCTYPE html>

<html>

<head>

<title>Edit Product</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="main-content">

<h1>Edit Product</h1>

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

<?php

while($row=mysqli_fetch_assoc($categories))
{

?>

<option
value="<?php echo $row['id']; ?>"

<?php

if($row['id']==$product['category_id'])
{
    echo "selected";
}

?>

>

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
value="<?php echo htmlspecialchars($product['product_name']); ?>"
required>

<br>

<label>Price</label>

<input
type="number"
step="0.01"
name="price"
value="<?php echo $product['price']; ?>"
required>

<br>

<label>Stock</label>

<input
type="number"
name="stock"
value="<?php echo $product['stock']; ?>"
required>

<br><br>

<button
type="submit"
name="update">

Update Product

</button>

<a
href="products.php"
class="button">

Cancel

</a>

</form>

</div>

</div>

</body>

</html>