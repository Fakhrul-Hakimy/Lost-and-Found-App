<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

$uploadDir = 'uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$itemId = $_POST['itemId'];
$itemName = $_POST['itemName'];
$category = $_POST['category'];
$description = $_POST['description'];
$dateFound = $_POST['dateFound'];
$fname = $_POST['fname'];
$fcontact = $_POST['fcontact'];

$image = $_FILES['image']['name'];
$imageTmpName = $_FILES['image']['tmp_name'];
$imageSize = $_FILES['image']['size'];
$imageError = $_FILES['image']['error'];
$imageType = $_FILES['image']['type'];

$imageExt = strtolower(pathinfo($image, PATHINFO_EXTENSION));
$allowed = array('jpg', 'jpeg', 'png', 'gif');

if (in_array($imageExt, $allowed)) {
    if ($imageError === 0) {
        if ($imageSize < 1000000) { // Limit size to 1MB
            $imageNewName = uniqid('', true) . "." . $imageExt;
            $imageDestination = $uploadDir . $imageNewName;
            if (!move_uploaded_file($imageTmpName, $imageDestination)) {
                echo json_encode(["status" => "error", "message" => "Failed to move uploaded file."]);
                exit();
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Your file is too big!"]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "There was an error uploading your file!"]);
        exit();
    }
} else {
    echo json_encode(["status" => "error", "message" => "You cannot upload files of this type!"]);
    exit();
}

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

$sql = "UPDATE lost_items SET item_name='$itemName', category='$category', description='$description', date_found='$dateFound', finder_name='$fname', finder_contact='$fcontact', image_path='$imageDestination' WHERE id='$itemId'";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Record updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $sql . "<br>" . $conn->error]);
}

$conn->close();
?>
