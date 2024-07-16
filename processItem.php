<?php
session_start();
include 'db_connection.php'; // Include your database connection file

if (!isset($_SESSION['username'])) {
    echo "<script>alert('No session Found. Please login first.'); window.location.href = 'index.html';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $itemName = $_POST['ItemName'];
    $category = $_POST['Category'];
    $description = $_POST['Description'];
    $dateFound = $_POST['date'];
    $findersName = $_POST['FName'];
    $findersContact = $_POST['Fnumber'];

    // File upload handling
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = './upload_files/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $fileUploaded = true;
        } else {
            $fileUploaded = false;
        }
    }

    // Database insertion
    $conn = OpenCon(); // Open database connection

    $stmt = $conn->prepare("INSERT INTO items (item_name, category, description, date_found, finders_name, finders_contact, file_name, file_path, file_type, file_size) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssi", $itemName, $category, $description, $dateFound, $findersName, $findersContact, $fileName, $dest_path, $fileType, $fileSize);

    if ($stmt->execute()) {
        echo "<script>alert('Item added successfully'); window.location.href = 'addItem.html';</script>";
    } else {
        echo "<script>alert('Error adding item'); window.location.href = 'addItem.html';</script>";
    }

    $stmt->close();
    CloseCon($conn); // Close database connection
}
?>
