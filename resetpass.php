<?php
session_start(); // Start the session at the beginning of the script

$servername = "localhost";
$db_username = "root"; // Database username
$db_password = "admin"; // Database password
$dbname = "LostFound";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully<br>";
}

// Assuming you've sanitized and validated $_POST['username'] and $_POST['password']
$username = $conn->real_escape_string($_POST['username']);
$username = $conn->real_escape_string($_POST['Email']);

// Construct SQL query with proper column selection and variable substitution
$sql = "SELECT * FROM users WHERE username = '$username' AND ' email = '$Email";

// Perform query
$result = $conn->query($sql);



// Close connection
$conn->close();
?>
