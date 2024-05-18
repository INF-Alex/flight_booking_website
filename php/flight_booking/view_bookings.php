<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT user_id FROM user WHERE username='$username'";
$result = $conn->query($sql);
$user_id = $result->fetch_assoc()['user_id'];

$sql = "SELECT ticket.*, flight.departure, flight.arrival, flight.flight_no 
        FROM ticket 
        JOIN flight ON ticket.flight_id = flight.flight_id 
        WHERE user_id='$user_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
</head>
<body>
    <h2>My Bookings</h2>
    <table border="1">
        <tr>
            <th>Ticket ID</th>
            <th>Flight No</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>Seat</th>
            <th>Price</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['ticket_id']; ?></td>
            <td><?php echo $row['flight_no']; ?></td>
            <td><?php echo $row['departure']; ?></td>
            <td><?php echo $row['arrival']; ?></td>
            <td><?php echo $row['seat']; ?></td>
            <td><?php echo $row['price']; ?></td>
        </tr>
        <?php } ?>
    </table>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
