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

// Assuming you've sanitized and validated $_POST['username'] and $_POST['password']
$username = $conn->real_escape_string($_POST['username']);
$password = $conn->real_escape_string($_POST['password']);

// Construct SQL query with proper column selection and variable substitution
$sql = "SELECT * FROM users WHERE username = '$username'";

// Perform query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, check password
    $row = $result->fetch_assoc();
    
    // Verify the password using password_verify() if you're using hashed passwords
    // Example assuming password stored as plaintext (not recommended)
    if ($row['password'] == $password) {
        // Password matches, redirect to main.php
        header("Location: http://localhost/site/main.php");
        exit(); // Ensure that subsequent code is not executed
    } else {
        echo "Incorrect password";
    }
} else {
    echo "User not found";
}

// Close connection
$conn->close();

?>
