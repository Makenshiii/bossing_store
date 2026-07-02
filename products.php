<?php
include 'config.php';
include 'auth.php';

requireLogin();

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$sql = "
SELECT
    products.id,
    products.product_name,
    products.price,
    products.stock,
    categories.category_name
FROM products
INNER JOIN categories
ON products.category_id = categories.id
WHERE products.product_name LIKE ?
ORDER BY products.id DESC
";

$stmt = $conn->prepare($sql);

$searchParam = "%" . $search . "%";
$stmt->bind_param("s", $searchParam);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Products | Bossing Store</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="main-content">

<h1>Products</h1>

<?php

if(isset($_GET['success']))
{

    if($_GET['success']=="added")
    {
        echo "<div class='success'>
        Product added successfully.
        </div>";
    }

    if($_GET['success']=="updated")
    {
        echo "<div class='success'>
        Product updated successfully.
        </div>";
    }

    if($_GET['success']=="deleted")
    {
        echo "<div class='success'>
        Product deleted successfully.
        </div>";
    }

}

?>

<br>

<form method="GET">

<input
type="text"
name="search"
class="search-box"
placeholder="Search Product..."
value="<?php echo htmlspecialchars($search); ?>">

<button
type="submit"
class="button">

Search

</button>

<?php if($search!=""){ ?>

<a
href="products.php"
class="button">

Clear

</a>

<?php } ?>

</form>

<br>

<?php if(isAdmin()){ ?>

<a
href="add_product.php"
class="button">

+ Add Product

</a>

<?php } ?>

<br><br>

<table>

<tr>

<th>ID</th>

<th>Category</th>

<th>Product Name</th>

<th>Price</th>

<th>Stock</th>

<?php if(isAdmin()){ ?>

<th>Action</th>

<?php } ?>

</tr>

<?php

if($result->num_rows>0)
{

while($row=$result->fetch_assoc())
{

?>

<tr>

<td>

<?php echo $row['id']; ?>

</td>

<td>

<?php echo htmlspecialchars($row['category_name']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['product_name']); ?>

</td>

<td>

₱<?php echo number_format($row['price'],2); ?>

</td>

<td>

<?php echo $row['stock']; ?>

</td>

<?php if(isAdmin()){ ?>

<td>

<a
href="edit_product.php?id=<?php echo $row['id']; ?>"
class="button">

Edit

</a>

<a
href="delete_product.php?id=<?php echo $row['id']; ?>"
class="button"
onclick="return confirm('Are you sure you want to delete this product?');">

Delete

</a>

</td>

<?php } ?>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="<?php echo isAdmin() ? 6 : 5; ?>">

No products found.

</td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>