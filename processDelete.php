<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Database connection details
$servername = "localhost";
$db_username = "root";
$db_password = "admin";
$dbname = "LostFound";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Get the item ID from the POST data
$data = json_decode(file_get_contents("php://input"), true);
$itemId = $data['id'];

// Delete the item from the database
$sql = "DELETE FROM lost_items WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die(json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]));
}
$stmt->bind_param("i", $itemId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(["status" => "success", "message" => "Item deleted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Item not found or could not be deleted"]);
}

$stmt->close();
$conn->close();
?>
