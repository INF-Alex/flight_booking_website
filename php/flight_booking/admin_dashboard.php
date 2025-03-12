<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 获取用户信息
$username = $_SESSION['username'];
$sql = "SELECT * FROM user WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

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

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            background-image: url('airplane.jpeg'); /* 替换为你的背景图片路径 */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #333;
        }
        .admin-dashboard-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        .admin-dashboard-container h2, .admin-dashboard-container h3, .admin-dashboard-container a {
            margin: 10px 0;
        }
        .admin-dashboard-container a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
            display: block;
        }
        .admin-dashboard-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="admin-dashboard-container">
        <h2>Menu</h2>
        <ul>
            <li><a href="register.php">Add User</a></li>
            <li><a href="delete_user.php">Delete User</a></li>
            <li><a href="update_user.php">Update User</a></li>
            <li><a href="admin_view_bookings.php">View Bookings</a></li>
            <li><a href="back_up.php">Back Up</a></li>
            <li><a href="restore.php">Restore</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
</body>
</html>
