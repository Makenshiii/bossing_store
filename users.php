<?php
include 'config.php';
include 'auth.php';

requireAdmin();

$search = "";

if(isset($_GET['search']))
{
    $search = trim($_GET['search']);
}

$sql = "
SELECT *
FROM users
WHERE username LIKE ?
OR email LIKE ?
ORDER BY id DESC
";

$stmt = $conn->prepare($sql);

$searchText = "%".$search."%";

$stmt->bind_param(
    "ss",
    $searchText,
    $searchText
);

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html>

<head>

<title>Users</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<?php include 'navbar.php'; ?>

<div class="main-content">

<h1>User Management</h1>

<?php

if(isset($_GET['success']))
{

    if($_GET['success']=="deleted")
    {
        echo "<div class='success'>
        User deleted successfully.
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
placeholder="Search Username..."
value="<?php echo htmlspecialchars($search); ?>">

<button
type="submit"
class="button">

Search

</button>

<?php

if($search!="")
{

?>

<a
href="users.php"
class="button">

Clear

</a>

<?php

}

?>

</form>

<br>

<a
href="register.php"
class="button">

+ Register User

</a>

<br><br>

<table>

<tr>

<th>ID</th>

<th>Username</th>

<th>Email</th>

<th>Role</th>

<th>Created</th>

<th>Action</th>

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

<?php echo htmlspecialchars($row['username']); ?>

</td>

<td>

<?php echo htmlspecialchars($row['email']); ?>

</td>

<td>

<?php echo $row['role']; ?>

</td>

<td>

<?php echo $row['created_at']; ?>

</td>

<td>

<?php

if($row['id'] != $_SESSION['user_id'])
{

?>

<a
class="button"
href="delete_user.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this user?')">

Delete

</a>

<?php

}
else
{

echo "<strong>Current User</strong>";

}

?>

</td>

</tr>

<?php

}

}
else
{

?>

<tr>

<td colspan="6">

No users found.

</td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>