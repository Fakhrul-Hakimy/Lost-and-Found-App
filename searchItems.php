<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
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

// Retrieve search queries if available
$name_query = isset($_GET['name']) ? $_GET['name'] : '';
$category_query = isset($_GET['category']) ? $_GET['category'] : '';

// Fetch records based on search queries
$sql = "SELECT * FROM lost_items WHERE 1=1";
if ($name_query) {
    $sql .= " AND item_name LIKE '%" . $conn->real_escape_string($name_query) . "%'";
} elseif ($category_query) {
    $sql .= " AND category = '" . $conn->real_escape_string($category_query) . "'";
}

$result = $conn->query($sql);

$records = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
}

$conn->close();

// Return JSON response
echo json_encode($records);
?>
