<?php
include 'config.php';
include 'auth.php';

requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: categories.php");
    exit();
}

$id = intval($_GET['id']);

// Check if category exists
$check = $conn->prepare("SELECT id FROM categories WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows == 0) {
    header("Location: categories.php");
    exit();
}

// Check if category is used by any product
$checkProduct = $conn->prepare("
SELECT id
FROM products
WHERE category_id = ?
LIMIT 1
");

$checkProduct->bind_param("i", $id);
$checkProduct->execute();

$productResult = $checkProduct->get_result();

if ($productResult->num_rows > 0) {

    echo "<script>
            alert('Cannot delete this category because it is being used by one or more products.');
            window.location='categories.php';
          </script>";
    exit();

}

// Delete category
$delete = $conn->prepare("
DELETE FROM categories
WHERE id = ?
");

$delete->bind_param("i", $id);

if ($delete->execute()) {

    header("Location: categories.php?success=deleted");
    exit();

} else {

    echo "<script>
            alert('Failed to delete category.');
            window.location='categories.php';
          </script>";

}
?>