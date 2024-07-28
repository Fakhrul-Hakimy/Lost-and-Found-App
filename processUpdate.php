<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

// Define the upload directory
$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Get POST data
$itemId = $_POST['itemId'];
$itemName = $_POST['itemName'];
$category = $_POST['category'];
$description = $_POST['description'];
$dateFound = $_POST['dateFound'];
$fname = $_POST['fname'];
$fcontact = $_POST['fcontact'];

// Handle file upload
$image = $_FILES['image']['name'];
$imageTmpName = $_FILES['image']['tmp_name'];
$imageSize = $_FILES['image']['size'];
$imageError = $_FILES['image']['error'];
$imageType = $_FILES['image']['type'];

$imageExt = strtolower(pathinfo($image, PATHINFO_EXTENSION));
$allowed = array('jpg', 'jpeg', 'png', 'gif');

if (!in_array($imageExt, $allowed)) {
    echo json_encode(["status" => "error", "message" => "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed."]);
    exit();
}

if ($imageError !== 0) {
    echo json_encode(["status" => "error", "message" => "Error uploading the file."]);
    exit();
}

if ($imageSize > 1000000) { // 1MB file size limit
    echo json_encode(["status" => "error", "message" => "File size exceeds 1MB limit."]);
    exit();
}

$imageNewName = uniqid('', true) . "." . $imageExt;
$imageDestination = $uploadDir . $imageNewName;
if (!move_uploaded_file($imageTmpName, $imageDestination)) {
    echo json_encode(["status" => "error", "message" => "Failed to move uploaded file."]);
    exit();
}

// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "admin";
$dbname = "LostFound";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Prepare and bind
$stmt = $conn->prepare("UPDATE lost_items SET item_name=?, category=?, description=?, date_found=?, finder_name=?, finder_contact=?, image_path=? WHERE id=?");
$stmt->bind_param("sssssssi", $itemName, $category, $description, $dateFound, $fname, $fcontact, $imageDestination, $itemId);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Record updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error updating record: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
