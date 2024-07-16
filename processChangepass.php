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

// Ensure the session has a username set
if (!isset($_SESSION['username'])) {
    echo "<script>alert('You are not logged in. Please log in first.'); window.location.href = 'index.html';</script>";
    exit();
}

// Sanitize and validate inputs
$username = $conn->real_escape_string($_SESSION['username']);
$oldpassword = $_POST['oldpass'];
$newpassword = $_POST['newpass'];

// Prepare and bind the SELECT statement to prevent SQL injection
$sql = "SELECT password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User found, check password
    $row = $result->fetch_assoc();
    
    // Verify the password using password_verify() if you're using hashed passwords
    if (password_verify($oldpassword, $row['password'])) {

        // Hash the new password before storing it
        $hashed_password = password_hash($newpassword, PASSWORD_BCRYPT);

        // Update the user's password
        $update_sql = "UPDATE users SET password = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $hashed_password, $username);
        
        // Perform query
        if ($update_stmt->execute()) {
            echo "<script>alert('Password Reset Successful.'); window.location.href = 'main.php';</script>";
        } else {
            echo "<script>alert('Error updating password.'); window.location.href = 'main.php';</script>";
        }

        $update_stmt->close();
    } else {
        echo "<script>alert('Incorrect Password'); window.location.href = 'main.php';</script>";
    }
} else {
    echo "<script>alert('User not found. Please Register'); window.location.href = 'main.php';</script>";
}

$stmt->close();
$conn->close();
?>
