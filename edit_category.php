<?php
include 'config.php';
include 'auth.php';

requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: categories.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("Location: categories.php");
    exit();
}

$category = $result->fetch_assoc();

$error = "";

if (isset($_POST['update'])) {

    $category_name = trim($_POST['category']);

    if (empty($category_name)) {

        $error = "Category name is required.";

    } else {

        // Check duplicate category
        $check = $conn->prepare("
            SELECT id
            FROM categories
            WHERE category_name = ?
            AND id != ?
        ");

        $check->bind_param("si", $category_name, $id);
        $check->execute();

        if ($check->get_result()->num_rows > 0) {

            $error = "Category already exists.";

        } else {

            $update = $conn->prepare("
                UPDATE categories
                SET category_name = ?
                WHERE id = ?
            ");

            $update->bind_param("si", $category_name, $id);

            if ($update->execute()) {

                header("Location: categories.php?success=updated");
                exit();

            } else {

                $error = "Failed to update category.";

            }

        }

    }

}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Category</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="main-content">

<h1>Edit Category</h1>

<br>

<?php

if($error!="")
{
    echo "<div class='error'>$error</div>";
}

?>

<div class="form-container">

<form method="POST">

<label>Category Name</label>

<input
type="text"
name="category"
value="<?php echo htmlspecialchars($category['category_name']); ?>"
required>

<br><br>

<button
type="submit"
name="update">

Update Category

</button>

<a
href="categories.php"
class="button">

Cancel

</a>

</form>

</div>

</div>

</body>

</html>
