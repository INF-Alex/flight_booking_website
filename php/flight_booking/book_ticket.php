<?php
include 'db.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flight_id = $_POST["flight_id"];
    $seat = $_POST["seat"];
    $price = $_POST["price"];
    $user_id = (int) $_SESSION['user_id']; // Assuming you store user_id in session after login
    $passenger_id = $_POST["passenger_id"];

    $sql = "INSERT INTO ticket (seat, price, flight_id, user_id, passenger_id) VALUES ('$seat', '$price', '$flight_id', '$user_id', '$passenger_id')";

    if ($conn->query($sql) === TRUE) {
        echo "Ticket booked successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Book Ticket</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        Flight ID: <input type="text" name="flight_id"><br>
        Seat: <input type="text" name="seat"><br>
        Price: <input type="text" name="price"><br>
        Passenger ID: <input type="text" name="passenger_id"><br>
        <input type="submit">
    </form>
</body>
</html>
