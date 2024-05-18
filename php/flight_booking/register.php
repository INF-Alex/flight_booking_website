<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $dob = $_POST["dob"];
    $sex = $_POST["sex"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $username = $_POST["username"];
    $ob_pw = $_POST["password"];
    $password = md5($_POST["password"]);

    $sql = "INSERT INTO user (firstname, lastname, dob, sex, email, phone, username, password) VALUES ('$firstname', '$lastname', '$dob', '$sex', '$email', '$phone', '$username', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful";
        // $_SESSION['username'] = $username;
        // $_SESSION['password'] = $_POST["password"];
        echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 500);</script>";

        echo "<script>";
        echo "window.onload = function() {";
        echo "  var form = document.createElement('form');";
        echo "  form.method = 'post';";
        echo "  form.action = 'login.php';";
        echo "  var usernameField = document.createElement('input');";
        echo "  usernameField.type = 'hidden';";
        echo "  usernameField.name = 'username';";
        echo "  usernameField.value = '$username';";
        echo "  form.appendChild(usernameField);";
        echo "  var passwordField = document.createElement('input');";
        echo "  passwordField.type = 'hidden';";
        echo "  passwordField.name = 'password';";
        echo "  passwordField.value = '$ob_pw';";
        echo "  form.appendChild(passwordField);";
        echo "  document.body.appendChild(form);";
        echo "  form.submit();";
        echo "}";
        echo "</script>";
        
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<body>
    <h2>Register</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
        First name: <input type="text" name="firstname"><br>
        Last name: <input type="text" name="lastname"><br>
        DOB: <input type="date" name="dob"><br>
        Sex: <input type="text" name="sex"><br>
        Email: <input type="text" name="email"><br>
        Phone: <input type="text" name="phone"><br>
        Username: <input type="text" name="username"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit">
    </form>
</body>
</html>
