<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('airplane.jpeg');
            background-size: cover;
            background-position: center;
            text-align: center;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            margin-top: 20px;
        }
        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
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

        // 处理用户提交的删除操作
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if(isset($_POST['delete_users']) && !empty($_POST['delete_users'])){
                foreach($_POST['delete_users'] as $user_id){
                    // 1. 删除与给定用户ID相关的所有外键关联的记录
                    $sql_delete_related = "DELETE FROM ticket WHERE user_id='$user_id'";
                    if ($conn->query($sql_delete_related) !== TRUE) {
                        echo "Error deleting related records for user with ID: " . $user_id . "<br>" . $conn->error;
                    } else {
                        // 2. 删除用户记录本身
                        $sql_delete_user = "DELETE FROM user WHERE user_id='$user_id'";
                        if ($conn->query($sql_delete_user) !== TRUE) {
                            echo "Error deleting user with ID: " . $user_id . "<br>" . $conn->error;
                        } else {
                            echo "User with ID " . $user_id . " deleted successfully.<br>";
                        }
                    }
                }
                echo "Selected users deleted successfully.";
            } else {
                echo "Please select at least one user to delete.";
            }
        }

        // 获取普通用户信息
        $sql = "SELECT * FROM user WHERE admin_tag = 0"; // 获取所有普通用户
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // 显示普通用户信息并提供多选框选择要删除的用户
            echo "<h2>Delete User</h2>";
            echo "<form method='post' action='".$_SERVER['PHP_SELF']."'>";
            echo "<table>";
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

        echo '<a href="admin_dashboard.php">Back</a>';
        $conn->close();
        ?>
    </div>
</body>
</html>
