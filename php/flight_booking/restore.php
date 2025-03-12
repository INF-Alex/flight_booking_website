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

$message = '';

if (isset($_POST['restore'])) {
    $backupFile = $_POST['backup_file'];
    include 'db.php';
    // 确保文件存在并且位于预期的目录中
    if (strpos($backupFile, '/var/www/html/flight_booking/backup/') === 0 && file_exists($backupFile)) {
        $command = "mysql -u $username -p$password $dbname < $backupFile";
        
        exec($command, $output, $result);
        
        if ($result == 0) {
            $message = "Restore successful!";
        } else {
            $message = "Restore failed!<br>Command: $command<br>Output: <pre>" . implode("\n", $output) . "</pre>";
        }
    } else {
        $message = "Invalid backup file.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Restore</title>
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
        }
        .restore-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        .restore-container h2, .restore-container form, .restore-container a {
            margin: 10px 0;
        }
        .restore-container label {
            display: block;
            margin-bottom: 10px;
        }
        .restore-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
        }
        .restore-container input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .restore-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .restore-container a {
            color: #4CAF50;
            text-decoration: none;
            display: block;
            margin-top: 20px;
        }
        .restore-container a:hover {
            text-decoration: underline;
        }
        .message {
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="restore-container">
        <h2>Restore Database</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="post">
            <label for="backup_file">Backup File:</label>
            <select name="backup_file" id="backup_file">
                <?php
                $files = glob('/var/www/html/flight_booking/backup/*.sql');
                foreach ($files as $file) {
                    $fileName = basename($file);
                    echo "<option value=\"$file\">$fileName</option>";
                }
                ?>
            </select>
            <input type="submit" name="restore" value="Restore">
        </form>
        <a href="admin_dashboard.php">Back</a>
    </div>
</body>
</html>
