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
    if ($user['admin_tag'] == 1) {
        header("Location: admin_dashboard.php");
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
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $user['firstname'] . ' ' . $user['lastname']; ?></h2>
    <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
    <p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>

    <h3>Menu</h3>
    <ul>
        <li><a href="search_flights.php">Search Flights</a></li>
        <li><a href="book_ticket.php">Book Ticket</a></li>
        <li><a href="view_bookings.php">View Bookings</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>
