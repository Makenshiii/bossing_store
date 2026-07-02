<?php
include 'config.php';
include 'auth.php';

requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$id = intval($_GET['id']);

// Prevent deleting yourself
if ($id == $_SESSION['user_id']) {
    echo "<script>
            alert('You cannot delete your own account.');
            window.location='users.php';
          </script>";
    exit();
}

// Check if user exists
$stmt = $conn->prepare("
SELECT *
FROM users
WHERE id=?
");

$stmt->bind_param("i",$id);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows==0)
{
    header("Location: users.php");
    exit();
}

$user = $result->fetch_assoc();

// Prevent deleting the last Admin
if($user['role']=="Admin")
{

    $admin = mysqli_query($conn,"
    SELECT COUNT(*) AS total
    FROM users
    WHERE role='Admin'
    ");

    $count = mysqli_fetch_assoc($admin);

    if($count['total']<=1)
    {
        echo "<script>
                alert('Cannot delete the last Admin account.');
                window.location='users.php';
              </script>";
        exit();
    }

}

// Delete user
$delete = $conn->prepare("
DELETE FROM users
WHERE id=?
");

$delete->bind_param("i",$id);

if($delete->execute())
{

    header("Location: users.php?success=deleted");
    exit();

}
else
{

    echo "<script>
            alert('Unable to delete user.');
            window.location='users.php';
          </script>";

}
?>