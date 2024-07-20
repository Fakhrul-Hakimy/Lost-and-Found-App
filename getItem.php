<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow all origins
header('Access-Control-Allow-Methods: POST, OPTIONS'); // Allow POST and OPTIONS requests
header('Access-Control-Allow-Headers: Content-Type'); // Allow Content-Type header
header('Content-Type: application/json');

$servername = "localhost";
$db_username = "root";
$db_password = "admin";
$dbname = "LostFound";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$sql = "SELECT * FROM lost_items";
$result = $conn->query($sql);

$items = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
}

$conn->close();

echo json_encode($items);
?>
