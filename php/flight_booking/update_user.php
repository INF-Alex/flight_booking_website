<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Update User</title>
<style>
    body {
        margin: 0;
        padding: 0;
        background-image: url('airplane.jpeg');
        background-size: cover;
        background-position: center;
        font-family: Arial, sans-serif;
    }
    .container {
        width: 80%;
        margin: 0 auto; /* 居中 */
        background-color: rgba(255, 255, 255, 0.8);
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    }
    h2 {
        margin-top: 0;
    }
    form {
        margin-top: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }
    td {
        text-align: left;
    }
</style>
</head>
<body>
<div class="container">
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["user_id"])) {
    // 如果是选择了要编辑的用户
    $id = $_POST["user_id"];
    $sql = "SELECT * FROM user WHERE user_id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $selectedUser = $result->fetch_assoc();
        // 显示编辑表单
        echo "<h2>Edit User</h2>";
        echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
        echo "<input type='hidden' name='id' value='".$selectedUser['user_id']."'>";
        echo "First name: <input type='text' name='firstname' value='".$selectedUser['firstname']."'><br>";
        echo "Last name: <input type='text' name='lastname' value='".$selectedUser['lastname']."'><br>";
        echo "DOB: <input type='date' name='dob' value='".$selectedUser['dob']."'><br>";
        echo "Sex: <input type='text' name='sex' value='".$selectedUser['sex']."'><br>";
        echo "Email: <input type='text' name='email' value='".$selectedUser['email']."'><br>";
        echo "Phone: <input type='text' name='phone' value='".$selectedUser['phone']."'><br>";
        echo "Username: <input type='text' name='username' value='".$selectedUser['username']."'><br>";
        echo "Password: <input type='password' name='password' value=''><br>";
        echo "Admin Tag: <input type='text' name='admin_tag' value='".$selectedUser['admin_tag']."'><br>";
        echo "<input type='submit' value='Update User'>";
        echo "</form>";
    } else {
        echo "User not found.";
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
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
    if($_POST["password"] == "")
        $sql = "UPDATE user SET firstname='$firstname', lastname='$lastname', dob='$dob', sex='$sex', email='$email', phone='$phone', username='$username', admin_tag='$admin_tag' WHERE user_id='$id'";
    else
        $sql = "UPDATE user SET firstname='$firstname', lastname='$lastname', dob='$dob', sex='$sex', email='$email', phone='$phone', username='$username', password='$password', admin_tag='$admin_tag' WHERE user_id='$id'";
    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully";
        echo "<script>setTimeout(function(){ window.location.href = 'update_user.php'; }, 500);</script>";
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
            echo '<a href="admin_dashboard.php">Back</a>';
            }
            $conn->close();
            ?>
            
            </div>
    </body>
</html>
