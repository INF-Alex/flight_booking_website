<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = $_POST["from"];
    $to = $_POST["to"];
    $date = $_POST["date"];

    $sql = "SELECT * FROM flight WHERE from_airport_id = (SELECT airport_id FROM airport WHERE iata='$from') AND to_airport_id = (SELECT airport_id FROM airport WHERE iata='$to') AND DATE(departure) = '$date'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo "Flight ID: " . $row["flight_id"]. " - Departure: " . $row["departure"]. " - Arrival: " . $row["arrival"]. "<br>";
        }
    } else {
        echo "No flights found";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Search Flights</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        From: <input type="text" name="from"><br>
        To: <input type="text" name="to"><br>
        Date: <input type="date" name="date"><br>
        <input type="submit">
    </form>
</body>
</html>
