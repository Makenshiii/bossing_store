<?php
include 'config.php';
include 'auth.php';

requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: sales.php");
    exit();
}

$id = intval($_GET['id']);

// Get Sale Information
$stmt = $conn->prepare("
SELECT *
FROM sales
WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: sales.php");
    exit();
}

$sale = $result->fetch_assoc();

$product_id = $sale['product_id'];
$quantity = $sale['quantity'];

// Get Current Product Stock
$product = $conn->prepare("
SELECT stock
FROM products
WHERE id = ?
");

$product->bind_param("i", $product_id);
$product->execute();

$productResult = $product->get_result();

if ($productResult->num_rows == 0) {
    header("Location: sales.php");
    exit();
}

$productData = $productResult->fetch_assoc();

$newStock = $productData['stock'] + $quantity;

// Restore Stock
$update = $conn->prepare("
UPDATE products
SET stock = ?
WHERE id = ?
");

$update->bind_param(
    "ii",
    $newStock,
    $product_id
);

$update->execute();

// Delete Sale
$delete = $conn->prepare("
DELETE FROM sales
WHERE id = ?
");

$delete->bind_param("i", $id);

if ($delete->execute()) {

    header("Location: sales.php?success=deleted");
    exit();

} else {

    echo "<script>
            alert('Unable to delete sale.');
            window.location='sales.php';
          </script>";

}
?>