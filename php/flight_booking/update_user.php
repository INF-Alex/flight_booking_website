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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 如果是提交修改后的用户信息
    $id = $_POST["id"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $dob = $_POST["dob"];
    $sex = $_POST["sex"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $username = $_POST["username"];
    $password = md5($_POST["password"]);
    $admin_tag = $_POST["admin_tag"];

    $sql = "UPDATE user SET firstname='$firstname', lastname='$lastname', dob='$dob', sex='$sex', email='$email', phone='$phone', username='$username', password='$password', admin_tag='$admin_tag' WHERE user_id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    // 显示所有普通用户信息并提供单选框选择要修改的用户
    $sql = "SELECT * FROM user WHERE admin_tag = 0"; // 获取所有普通用户
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<h2>Update User</h2>";
        echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
        echo "<table border='1'>";
        echo "<tr><th>User ID</th><th>First Name</th><th>Last Name</th><th>DOB</th><th>Sex</th><th>Email</th><th>Phone</th><th>Username</th><th>Action</th></tr>";
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
            echo "<td><input type='radio' name='user_id' value='".$row['user_id']."'></td>"; // 单选框，用于选择要修改的用户
            echo "</tr>";
        }
        echo "</table>";
        echo "<input type='submit' value='Edit Selected User'>";
        echo "</form>";
    } else {
        echo "No users found.";
    }
}

$conn->close();
?>
