<?php
include 'config.php';
include 'auth.php';

requireLogin();

$search = "";

if(isset($_GET['search']))
{
    $search = trim($_GET['search']);
}

$sql = "
SELECT
sales.id,
products.product_name,
sales.quantity,
sales.total_price,
sales.sale_date
FROM sales
INNER JOIN products
ON sales.product_id = products.id
WHERE products.product_name LIKE ?
ORDER BY sales.sale_date DESC
";

$stmt = $conn->prepare($sql);

$searchParam = "%".$search."%";
$stmt->bind_param("s",$searchParam);
$stmt->execute();

$result = $stmt->get_result();

// Total Sales Amount
$totalQuery = mysqli_query($conn,"
SELECT SUM(total_price) AS total
FROM sales
");

$totalSales = mysqli_fetch_assoc($totalQuery);
?>

<!DOCTYPE html>

<html>

<head>

<title>Sales</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="main-content">

<h1>Sales Records</h1>

<?php

if(isset($_GET['success']))
{

    if($_GET['success']=="added")
    {
        echo "<div class='success'>
        Sale recorded successfully.
        </div>";
    }

    if($_GET['success']=="deleted")
    {
        echo "<div class='success'>
        Sale deleted successfully.
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
href="sales.php"
class="button">

Clear

</a>

<?php } ?>

</form>

<br>

<?php if(isAdmin()){ ?>

<a
href="add_sale.php"
class="button">

+ Record Sale

</a>

<?php } ?>

<br><br>

<h3>

Total Sales Amount:

₱<?php echo number_format($totalSales['total'],2); ?>

</h3>

<br>

<table>

<tr>

<th>ID</th>

<th>Product</th>

<th>Quantity</th>

<th>Total</th>

<th>Date</th>

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

<?php echo htmlspecialchars($row['product_name']); ?>

</td>

<td>

<?php echo $row['quantity']; ?>

</td>

<td>

₱<?php echo number_format($row['total_price'],2); ?>

</td>

<td>

<?php echo $row['sale_date']; ?>

</td>

<?php if(isAdmin()){ ?>

<td>

<a
class="button"
href="delete_sale.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this sale?')">

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

No sales found.

</td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>