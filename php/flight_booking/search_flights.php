<!DOCTYPE html>
<html>

<head>
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

        th,
        td {
            padding: 10px;
        }

        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <?php
    include 'db.php';
    session_start();

    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }

    $booking_successful = false;
    $flight_details = [];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = json_decode($_POST["flightData"], true); // 解析JSON数据
        $passenger_id = $_POST["passenger_id"];

        if (isset($data['flight_id'])) {
            $flight_id = $data['flight_id'];

            $stmt2 = $conn->prepare("SELECT * FROM ticket WHERE flight_id = ? AND user_id = ? AND passenger_id = ?");
            $stmt2->bind_param("iii", $flight_id, $_SESSION['user_id'], $passenger_id);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            if ($result2->num_rows > 0) {
                echo "Repeat booking!" . "<br>";
            } else {
                $stmt = $conn->prepare("INSERT INTO ticket (flight_id, user_id, passenger_id) VALUES (?, ?, ?)");
                $stmt->bind_param("iii", $flight_id, $_SESSION['user_id'], $passenger_id); // 使用session中的用户ID
                if ($stmt->execute()) {
                    $sql = "SELECT flight.*, airplane.*
                        FROM flight
                        INNER JOIN airplane ON flight.airplane_id = airplane.airplane_id
                        WHERE flight.flight_id = '$flight_id'
                        ";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $flight_details = $result->fetch_assoc();
                        $booking_successful = true;
                    } else {
                        echo "Invalid flight_id";
                    }
                } else {
                    echo "Error: " . $stmt->error . "<br>";
                }
                $stmt->close();
            }
            $stmt2->close();
        } else {
            echo "Invalid data received.";
        }
    }
    ?>
    <?php if ($booking_successful): ?>
        <h2>Book Successfully</h2>
        <table border="1">
            <tr>
                <th>Flight</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Duration</th>
                <th>Airplane</th>
                <th>Capacity</th>
            </tr>
            <tr>
                <td><?php echo $flight_details['flight_no']; ?></td>
                <td><?php echo $flight_details['departure']; ?></td>
                <td><?php echo $flight_details['arrival']; ?></td>
                <td><?php echo $flight_details['duration']; ?></td>
                <td><?php echo $flight_details['type']; ?></td>
                <td><?php echo $flight_details['capacity']; ?></td>
            </tr>
        </table>
        <br>
        <a href="dashboard.php">Back</a>
    <?php else: ?>
        <div class="form-container">
            <h2>Book Tickets</h2>
            <form id="flightForm" method="post" action="">
                <table border='1'>
                    <tr>
                        <th>ID</th>
                        <th>Flight</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Book</th>
                    </tr>
                    <?php
                    $result = $conn->query("SELECT * FROM flight");

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["flight_id"] . "</td>";
                            echo "<td>" . $row["flight_no"] . "</td>";
                            echo "<td>" . $row["departure"] . "</td>";
                            echo "<td>" . $row["arrival"] . "</td>";
                            echo "<td>" . $row["from_airport_id"] . "</td>";
                            echo "<td>" . $row["to_airport_id"] . "</td>";
                            echo "<td><input type='radio' name='selectedFlight' value='" . htmlspecialchars(json_encode($row)) . "'></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No results found.</td></tr>";
                    }
                    ?>
                </table>
                <br>
                Passenger ID: <input type="text" name="passenger_id" required><br><br>
                <input type="hidden" id="flightDataInput" name="flightData">
                <button type="button" onclick="submitForm()">Book</button>
            </form>
            <a href="dashboard.php">Back</a>
        </div>
    <?php endif; ?>

    <script>
        function submitForm() {
            var selectedFlight = document.querySelector('input[name="selectedFlight"]:checked');
            if (selectedFlight) {
                var flightData = JSON.parse(selectedFlight.value);
                document.getElementById("flightDataInput").value = JSON.stringify(flightData);
                document.getElementById("flightForm").submit();
            } else {
                alert("Please select a flight to book.");
            }
        }
    </script>
</body>

</html>
