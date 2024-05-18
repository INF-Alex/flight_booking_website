<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}


// 获取用户信息
$username = $_SESSION['username'];
$sql = "SELECT * FROM user WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if ($user['admin_tag'] != 1) {
        header("Location: login.php");
        exit();
    }
} else {
    echo "User not found.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>

    <h3>Add User</h3>
    <a href="register.php">Add User</a><br>

    <h3>Delete User</h3>
    <a href="delete_user.php">Delete User</a><br>

    <h3>Update User</h3>
    <a href="update_user.php">Update User</a><br>

    <a href="logout.php">Logout</a>
</body>
</html>
