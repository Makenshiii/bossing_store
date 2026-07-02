<?php
include 'config.php';
include 'auth.php';

requireAdmin();

$error = "";

if (isset($_POST['save'])) {

    $category = trim($_POST['category']);

    if (empty($category)) {

        $error = "Category name is required.";

    } else {

        // Check if category already exists
        $check = $conn->prepare("SELECT id FROM categories WHERE category_name = ?");
        $check->bind_param("s", $category);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $error = "Category already exists.";

        } else {

            $insert = $conn->prepare("
                INSERT INTO categories(category_name)
                VALUES(?)
            ");

            $insert->bind_param("s", $category);

            if ($insert->execute()) {

                header("Location: categories.php?success=added");
                exit();

            } else {

                $error = "Failed to add category.";

            }

        }

    }

}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Category</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="main-content">

<h1>Add Category</h1>

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
placeholder="Enter Category Name"
required>

<br><br>

<button
type="submit"
name="save">

Save Category

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