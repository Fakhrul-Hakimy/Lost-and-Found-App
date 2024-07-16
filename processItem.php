<?php
session_start();

$servername = "localhost";
$db_username = "root"; // Database username
$db_password = "admin"; // Database password
$dbname = "LostFound";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in by checking the session variable
if (!isset($_SESSION['username'])) {
    echo "No session found. Please log in first.";
    exit;
}

// Check if form data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemName = $_POST['ItemName'];
    $category = $_POST['Category'];
    $description = $_POST['Description'];
    $dateFound = $_POST['date'];
    $finderName = $_POST['FName'];
    $finderContact = $_POST['Fnumber'];
    $capturedImageData = $_POST['capturedImageData'];

    // Decode the base64 image data
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $capturedImageData));
    $imageName = 'uploads/' . uniqid() . '.png';

    // Ensure the uploads directory exists
    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    // Save the image to the server
    if (file_put_contents($imageName, $imageData) !== false) {
        // Prepare the SQL statement to save item details and image path to the database
        $stmt = $conn->prepare("INSERT INTO items (name, category, description, date_found, finder_name, finder_contact, image_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("sssssss", $itemName, $category, $description, $dateFound, $finderName, $finderContact, $imageName);
            $stmt->execute();
            $stmt->close();
            echo "<script>alert('Item added'); window.location.href = 'main.php';</script>";
        } else {
            
            echo "<script>alert('Failed to prepare the SQL statement.'); window.location.href = 'main.php';</script>";
        }
    } else {
        echo "<script>alert('Failed to save images'); window.location.href = 'main.php';</script>";
    }
} else {
    echo "<script>alert('Invalid Request'); window.location.href = 'main.php';</script>";
}

$conn->close();
?>
