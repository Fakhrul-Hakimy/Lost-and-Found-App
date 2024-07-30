<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow all origins
header('Access-Control-Allow-Methods: POST, OPTIONS'); // Allow POST and OPTIONS requests
header('Access-Control-Allow-Headers: Content-Type'); // Allow Content-Type header

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database credentials
$servername = "localhost";
$db_username = "root";
$db_password = "admin";
$dbname = "LostFound";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['username']) || !isset($data['currentpass']) || !isset($data['newpass']) || !isset($data['email'])) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit;
}

$username = $conn->real_escape_string($data['username']);
$email = $conn->real_escape_string($data['email']);
$currentpass = $data['currentpass'];
$newpass = $data['newpass'];

// Check if the user exists and retrieve the stored password hash
$sql = "SELECT password FROM users WHERE username = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($stored_hash);
    $stmt->fetch();

    // Verify the old password
    if (password_verify($currentpass, $stored_hash)) {
        // Hash the new password
        $hashed_password = password_hash($newpass, PASSWORD_BCRYPT);

        // Update the password
        $update_sql = "UPDATE users SET password = ? WHERE username = ? AND email = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sss", $hashed_password, $username, $email);

        if ($update_stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Change Password Successful"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error updating password"]);
        }
        $update_stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Old password is incorrect"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "User not found"]);
}

$stmt->close();
$conn->close();
?>
