<?php
// Set headers for CORS and JSON response
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

// Validate input
if (!isset($data['email']) || !isset($data['username']) || !isset($data['password'])) {
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    exit;
}

$email = $conn->real_escape_string($data['email']);
$username = $conn->real_escape_string($data['username']);
$password = $conn->real_escape_string($data['password']);

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare SQL query using prepared statements
$sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $email, $username, $hashed_password);

// Execute the query and provide feedback
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Registration successful"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
