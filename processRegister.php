<?php

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "dbname";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully<br>";

// Assuming you've sanitized and validated $_POST['Email'], $_POST['username'], and $_POST['password']
$email = $conn->real_escape_string($_POST['Email']);
$username = $conn->real_escape_string($_POST['username']);
$password = $conn->real_escape_string($_POST['password']);

// Construct SQL query using prepared statement
$sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $email, $username, $password);

// Execute the query
if ($stmt->execute()) {
    echo "Register successful";
} else {
    echo "Error: " . $conn->error;
}

// Close statement and connection
$stmt->close();

?>
