<?php
session_start();
include 'db.php';

// 检查用户是否已登录
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

// 获取普通用户信息
$sql = "SELECT * FROM user WHERE admin_tag = 0"; // 获取所有普通用户
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 显示普通用户信息并提供多选框选择要删除的用户
    echo "<h2>Delete User</h2>";
    echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
    echo "<table border='1'>";
    echo "<tr><th>User ID</th><th>First Name</th><th>Last Name</th><th>DOB</th><th>Sex</th><th>Email</th><th>Phone</th><th>Username</th><th>Delete</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row['user_id']."</td>";
        echo "<td>".$row['firstname']."</td>";
        echo "<td>".$row['lastname']."</td>";
        echo "<td>".$row['dob']."</td>";
        echo "<td>".$row['sex']."</td>";
        echo "<td>".$row['email']."</td>";
        echo "<td>".$row['phone']."</td>";
        echo "<td>".$row['username']."</td>";
        echo "<td><input type='checkbox' name='delete_users[]' value='".$row['user_id']."'></td>"; // 多选框，用于选择要删除的用户
        echo "</tr>";
    }
    echo "</table>";
    echo "<input type='submit' value='Delete Selected Users'>";
    echo "</form>";
} else {
    echo "No users found.";
}

// 处理用户提交的删除操作
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['delete_users']) && !empty($_POST['delete_users'])){
        foreach($_POST['delete_users'] as $user_id){
            $sql = "DELETE FROM user WHERE user_id='$user_id'";
            if ($conn->query($sql) !== TRUE) {
                echo "Error deleting user with ID: " . $user_id . "<br>" . $conn->error;
            }
        }
        echo "Selected users deleted successfully.";
                
        // 刷新用户数据
        ob_clean();
        $sql = "SELECT * FROM user WHERE admin_tag = 0";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Delete User</h2>";
            echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
            echo "<table border='1'>";
            echo "<tr><th>User ID</th><th>First Name</th><th>Last Name</th><th>DOB</th><th>Sex</th><th>Email</th><th>Phone</th><th>Username</th><th>Delete</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['user_id']."</td>";
                echo "<td>".$row['firstname']."</td>";
                echo "<td>".$row['lastname']."</td>";
                echo "<td>".$row['dob']."</td>";
                echo "<td>".$row['sex']."</td>";
                echo "<td>".$row['email']."</td>";
                echo "<td>".$row['phone']."</td>";
                echo "<td>".$row['username']."</td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "<input type='submit' value='Delete Selected Users'>";
            echo "</form>";
        } else {
            echo "No users found.";
        }

        echo "<script>setTimeout(function(){ window.location.href = 'admin_dashboard.php'; }, 1000);</script>";
    } else {
        echo "Please select at least one user to delete.";
    }
}


$conn->close();
?>
