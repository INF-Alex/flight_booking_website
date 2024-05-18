<?php
include 'db.php';

session_start();

// 检查会话中是否已经存在登录状态和最后活动时间
if (isset($_SESSION['username']) && isset($_SESSION['last_activity'])) {
    // 检查最后活动时间是否超过10分钟
    $inactive_time = 10 * 60; // 10分钟（以秒为单位）
    $current_time = time();
    if (($current_time - $_SESSION['last_activity']) < $inactive_time) {
        // 最后活动时间未超过10分钟，重定向到仪表板页面
        header("Location: dashboard.php");
        exit();
    } else {
        // 最后活动时间超过10分钟，销毁会话并重新登录
        session_unset();
        session_destroy();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = md5($_POST["password"]);

    $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['username'] = $username;
        $_SESSION['last_activity'] = time(); // 更新最后活动时间
        header("Location: dashboard.php");
    } else {
        echo "Invalid username or password";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit">
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
