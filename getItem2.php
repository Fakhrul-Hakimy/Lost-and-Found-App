<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

$servername = "localhost";
$db_username = "root";
$db_password = "";
$dbname = "LostFound";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

$email = isset($_GET['email']) ? $conn->real_escape_string($_GET['email']) : '';
$itemId = isset($_GET['id']) ? $conn->real_escape_string($_GET['id']) : '';

if ($itemId) {
    $sql = "SELECT * FROM lost_items WHERE id = '$itemId' AND finder_contact = '$email'";
} else {
    $sql = "SELECT * FROM lost_items WHERE finder_contact = '$email'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    echo json_encode($items);
} else {
    echo json_encode([]);
}

$conn->close();
?>
