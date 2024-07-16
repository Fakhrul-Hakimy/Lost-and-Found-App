<?php
session_start(); // Start the session at the beginning of the script

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
$password = $_POST['password']; // No need to escape password for hashing

// Construct SQL query with proper column selection and variable substitution
$sql = "SELECT * FROM users WHERE username = '$username'";

// Perform query
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // User found, check password
    $row = $result->fetch_assoc();
    
    // Verify the password using password_verify() if you're using hashed passwords
    if (password_verify($password, $row['password'])) {
        // Password matches, store username in session
        $_SESSION['username'] = $username;
        
        // Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);
        
        // Redirect to main.php
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
