<?php
include 'config.php';
include 'auth.php';

requireAdmin();

$error = "";

$products = mysqli_query($conn,"
SELECT *
FROM products
WHERE stock > 0
ORDER BY product_name ASC
");

if(isset($_POST['save']))
{
    $product_id = intval($_POST['product']);
    $quantity = intval($_POST['quantity']);

    if($product_id == 0 || $quantity <= 0)
    {
        $error = "Please complete all fields.";
    }
    else
    {

        $productQuery = $conn->prepare("
        SELECT *
        FROM products
        WHERE id = ?
        ");

        $productQuery->bind_param("i",$product_id);
        $productQuery->execute();

        $product = $productQuery->get_result()->fetch_assoc();

        if(!$product)
        {
            $error = "Product not found.";
        }
        else
        {

            if($quantity > $product['stock'])
            {
                $error = "Not enough stock available.";
            }
            else
            {

                $total = $quantity * $product['price'];

                // Save Sale
                $insert = $conn->prepare("
                INSERT INTO sales
                (product_id,quantity,total_price)
                VALUES
                (?,?,?)
                ");

                $insert->bind_param(
                    "iid",
                    $product_id,
                    $quantity,
                    $total
                );

                if($insert->execute())
                {

                    // Update Stock
                    $newStock = $product['stock'] - $quantity;

                    $update = $conn->prepare("
                    UPDATE products
                    SET stock=?
                    WHERE id=?
                    ");

                    $update->bind_param(
                        "ii",
                        $newStock,
                        $product_id
                    );

                    $update->execute();

                    header("Location: sales.php?success=added");
                    exit();

                }
                else
                {
                    $error = "Failed to record sale.";
                }

            }

        }

    }

}
?>

<!DOCTYPE html>

<html>

<head>

<title>Record Sale</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="main-content">

<h1>Record Sale</h1>

<br>

<?php

if($error!="")
{
    echo "<div class='error'>$error</div>";
}

?>

<div class="form-container">

<form method="POST">

<label>Product</label>

<select name="product" required>

<option value="">Select Product</option>

<?php

while($row=mysqli_fetch_assoc($products))
{

?>

<option value="<?php echo $row['id']; ?>">

<?php

echo $row['product_name'];

?>

(
Stock:

<?php echo $row['stock']; ?>

)

</option>

<?php

}

?>

</select>

<br><br>

<label>Quantity</label>

<input
type="number"
name="quantity"
min="1"
required>

<br><br>

<button
type="submit"
name="save">

Save Sale

</button>

<a
href="sales.php"
class="button">

Cancel

</a>

</form>

</div>

</div>

</body>

</html>