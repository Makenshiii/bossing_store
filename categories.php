<?php
include 'config.php';
include 'auth.php';

requireLogin();

$search = "";

if(isset($_GET['search']))
{
    $search = trim($_GET['search']);
}

$sql = "SELECT *
        FROM categories
        WHERE category_name LIKE ?
        ORDER BY id DESC";

$stmt = $conn->prepare($sql);

$searchParam = "%".$search."%";
$stmt->bind_param("s",$searchParam);
$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>

<head>

    <title>Categories | Bossing Store</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="main-content">

<h1>Categories</h1>

<?php

if(isset($_GET['success']))
{

    if($_GET['success']=="added")
    {
        echo "<div class='success'>
        Category added successfully.
        </div>";
    }

    if($_GET['success']=="updated")
    {
        echo "<div class='success'>
        Category updated successfully.
        </div>";
    }

    if($_GET['success']=="deleted")
    {
        echo "<div class='success'>
        Category deleted successfully.
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
placeholder="Search Category..."
value="<?php echo htmlspecialchars($search); ?>">

<button
type="submit"
class="button">

Search

</button>

<?php if($search!=""){ ?>

<a
href="categories.php"
class="button">

Clear

</a>

<?php } ?>

</form>

<br>

<?php if(isAdmin()){ ?>

<a
href="add_category.php"
class="button">

+ Add Category

</a>

<?php } ?>

<br><br>

<table>

<tr>

<th>ID</th>

<th>Category Name</th>

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

<?php if(isAdmin()){ ?>

<td>

<a
href="edit_category.php?id=<?php echo $row['id']; ?>"
class="button">

Edit

</a>

<a
href="delete_category.php?id=<?php echo $row['id']; ?>"
class="button"
onclick="return confirm('Delete this category?');">

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

<td colspan="<?php echo isAdmin() ? 3 : 2; ?>">

No categories found.

</td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>
