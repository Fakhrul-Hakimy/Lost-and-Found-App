<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *"); // Allows requests from any origin
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allows specific methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allows specific headers


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$db_username = "root";
$db_password = "admin";
$dbname = "LostFound";

$conn = new mysqli($servername, $db_username, $db_password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["success" => false, "message" => "Invalid JSON input"]);
    exit;
}

if (!isset($data['email']) || !isset($data['username'])) {
    echo json_encode(["success" => false, "message" => "All fields are required"]);
    exit;
}

$email = $conn->real_escape_string($data['email']);
$username = $conn->real_escape_string($data['username']);

$sql = "SELECT * FROM users WHERE username = ? AND email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $resetpass = generateRandomSixDigitNumber();
    $hashed_password = password_hash($resetpass, PASSWORD_BCRYPT);

    $sql = "UPDATE users SET password = ? WHERE username = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $hashed_password, $username, $email);

    if ($stmt->execute()) {
        sendTacEmail($email, $resetpass, $username);
        
    } else {
        echo json_encode(["success" => false, "message" => "Error updating password"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "No user with that username and email"]);
}

$stmt->close();
$conn->close();

function generateRandomSixDigitNumber() {
    return mt_rand(100000, 999999);
}

function sendTacEmail($email, $resetpass, $username) {
    require 'C:/xampp/htdocs/site/PHPMailer-6.9.1/src/Exception.php';
    require 'C:/xampp/htdocs/site/PHPMailer-6.9.1/src/PHPMailer.php';
    require 'C:/xampp/htdocs/site/PHPMailer-6.9.1/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'fakhrulhakimy93@gmail.com';
        $mail->Password = 'jcouifynxpmymcuw';
        $mail->SMTPSecure = 'tls';

        $mail->setFrom('fakhrulhakimy93@gmail.com', 'Fakhrul');
        $mail->addAddress($email, $username);

        $mail->isHTML(true);
        $mail->Subject = 'Your Temp Password';
        $mail->Body = "Hello $username,<br><br>Your Temp Pass is: <b>$resetpass</b><br><br>Please use this password to log in and reset your password as soon as possible.";

        $mail->send();
        echo json_encode(["success" => true, "message" => "New Password has been sent to your email."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
    }
}
?>
