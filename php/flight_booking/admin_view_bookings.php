<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM user WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    header("Location: view_bookings.php");
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
        .booking-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .booking-container h2, .booking-container table, .booking-container a {
            margin: 10px 0;
        }
        .booking-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .booking-container th, .booking-container td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .booking-container th {
            background-color: #f2f2f2;
        }
        .booking-container a {
            color: #4CAF50;
            text-decoration: none;
            display: block;
            margin-top: 20px;
        }
        .booking-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="booking-container">
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
                <td><?php echo $row['firstname'] ."  " .$row['lastname']; ?></td>
                <td><?php echo $row['flight_no']; ?></td>
                <td><?php echo $row['departure']; ?></td>
                <td><?php echo $row['arrival']; ?></td>
                <td><?php echo $row['from_name']; ?></td>
                <td><?php echo $row['to_name']; ?></td>
            </tr>
            <?php } ?>
        </table>
        <a href="dashboard.php">Back</a>
    </div>
</body>
</html>
