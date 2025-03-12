<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// 获取用户信息
$appusername = $_SESSION['username'];
$sql = "SELECT * FROM user WHERE username='$appusername'";
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

// 确保备份目录存在并且有写权限
$backupDir = '/var/www/html/flight_booking/backup/';
if (!is_dir($backupDir)) {
    if (!mkdir($backupDir, 0777, true)) {
        echo "Failed to create backup directory.";
        exit();
    }
}
if (!is_writable($backupDir)) {
    echo "Backup directory is not writable.";
    exit();
}

// 数据库备份
$backupFile = $backupDir . date('Y_m_d_H_i_s') . '.sql';
$command = "mysqldump -u $username -p$password --single-transaction --skip-triggers $dbname > $backupFile";

// 执行命令并捕获输出
$output = [];
exec($command, $output, $result);

if ($result === 0) {
    $backupFileName = 'backup/' .basename($backupFile);
    $message = "Backup successful! <a href='$backupFileName'>Download Backup</a>";
} else {
    $message = "<h3>Backup failed!</h3><p>Command: $command</p><pre>" . implode("\n", $output) . "</pre>"; // 显示错误输出
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Back Up</title>
    <style>
        body {
            background-image: url('airplane.jpeg');
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
            text-align: center;
        }
        .message-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }
        .message-container a {
            color: #4CAF50;
            text-decoration: none;
            display: block; /* Ensure "Back" button takes full width */
            margin-top: 10px; /* Add some space between the message and the button */
        }
        .message-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="message-container">
        <h2>Back Up Database</h2>
        <?php echo $message; ?>
        <a href="admin_dashboard.php">Back</a>
    </div>
</body>
</html>
