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

$sql = "SELECT passenger.firstname, passenger.lastname, flight.flight_no, flight.departure, flight.arrival, p1.name AS from_name, p2.name AS to_name
        FROM ticket
        INNER JOIN passenger ON ticket.passenger_id = passenger.passenger_id
        INNER JOIN flight ON ticket.flight_id = flight.flight_id
        INNER JOIN airport AS p1 ON flight.from_airport_id = p1.airport_id
        INNER JOIN airport AS p2 ON flight.to_airport_id = p2.airport_id
        WHERE ticket.user_id = '$user_id'
        ";

$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings</title>
    <style>
        body {
            background-image: url('airplane.jpeg');
            background-size: cover;
            text-align: center;
            padding-top: 50px;
        }
        table {
            margin: auto;
            width: 80%;
            background-color: rgba(255, 255, 255, 0.8);
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        a {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: white;
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 5px;
            width: 100px;
            margin: 20px auto;
        }
        a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h2>My Bookings</h2>
    <table>
        <tr>
            <th>Passenger</th>
            <th>Flight</th>
            <th>Departure</th>
            <th>Arrival</th>
            <th>From</th>
            <th>To</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?php echo $row['firstname'] ." " .$row['lastname']; ?></td>
            <td><?php echo $row['flight_no']; ?></td>
            <td><?php echo $row['departure']; ?></td>
            <td><?php echo $row['arrival']; ?></td>
            <td><?php echo $row['from_name']; ?></td>
            <td><?php echo $row['to_name']; ?></td>
        </tr>
        <?php } ?>
    </table>
    <a href="dashboard.php">Back</a>
</body>
</html>
