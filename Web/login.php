<?php
$host = "localhost";
$username = "root";
$password = "Shasha@23";
$database = "users";

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Successful login
        header("Location: welcome.php"); // Redirect to a welcome page
    } else {
        // Invalid login
        echo "Invalid username or password.";
    }

    $conn->close();
}
?>