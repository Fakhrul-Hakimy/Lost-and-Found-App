<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
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

// Check if item ID is provided in the query parameters
if (isset($_GET['id'])) {
    $itemId = $_GET['id'];
    $sql = "SELECT * FROM lost_items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die(json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]));
    }
    $stmt->bind_param("i", $itemId);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    echo json_encode($item);
    $stmt->close();
} else {
    $sql = "SELECT * FROM lost_items";
    $result = $conn->query($sql);

    if ($result === false) {
        die(json_encode(["status" => "error", "message" => "Query failed: " . $conn->error]));
    }

    $items = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            // Adding item to the array
            $items[] = $row;
        }
    }
    echo json_encode($items);
}

$conn->close();
?>
