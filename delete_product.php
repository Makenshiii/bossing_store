<?php
include 'config.php';
include 'auth.php';

requireAdmin();

// Check if ID exists
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$id = intval($_GET['id']);

// Check if product exists
$check = $conn->prepare("SELECT id FROM products WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows == 0) {
    header("Location: products.php");
    exit();
}

// Delete Product
$delete = $conn->prepare("DELETE FROM products WHERE id = ?");
$delete->bind_param("i", $id);

if ($delete->execute()) {

    header("Location: products.php?success=deleted");
    exit();

} else {

    echo "<script>
            alert('Failed to delete product.');
            window.location='products.php';
          </script>";

}
?>