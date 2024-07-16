<?php
function OpenCon() {
    $servername = "localhost";
    $db_username = "root"; // Database username
    $db_password = "admin"; // Database password
    $dbname = "LostFound";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function CloseCon($conn) {
    $conn->close();
}
?>
