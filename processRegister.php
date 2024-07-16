<?php

$servername = "localhost";
$db_username = "root"; // Rename this variable to avoid conflict with the `username` column
$db_password = "admin";
$dbname = "LostFound";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if any of the fields are empty
if (empty($_POST['Email']) || empty($_POST['username']) || empty($_POST['password'])) {
    echo "<script>alert('Error: All fields are required.'); window.location.href = 'register.html';</script>";
    exit();
}

// Sanitize and validate input
$email = $conn->real_escape_string($_POST['Email']);
$username = $conn->real_escape_string($_POST['username']);
$password = $conn->real_escape_string($_POST['password']);

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Construct SQL query using prepared statement
$sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $email, $username, $hashed_password);

// Execute the query
if ($stmt->execute()) {
    echo "<script>alert('Registration successful'); window.location.href = 'index.html';</script>";
} else {
    echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'register.html';</script>";
}

// Close statement and connection
$stmt->close();
$conn->close();

?>
