<?php
session_start(); // Start the session at the beginning of the script

$servername = "localhost";
$db_username = "root"; // Database username
$db_password = "admin"; // Database password
$dbname = "LostFound";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully<br>";
}

// Check if POST variables are set
if (isset($_POST['username']) && isset($_POST['Email']) && !empty($_POST['username']) && !empty($_POST['Email'])) {
    // Sanitize and validate input
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['Email']);

    // Construct SQL query using prepared statements for security
    $sql = "SELECT * FROM users WHERE username = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);

    // Perform query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $resetpass = generateRandomSixDigitNumber();

        // Hash the password before storing it
        $hashed_password = password_hash($resetpass, PASSWORD_BCRYPT);

        // Update the user's password
        $sql = "UPDATE users SET password = ? WHERE username = ? AND email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $hashed_password, $username, $email);

        // Perform query
        if ($stmt->execute()) {
            sendTacEmail($email, $resetpass, $username);
            echo "<script>alert('Password Reset Successful. Please Check your Email.'); window.location.href = 'index.html';</script>";
        } else {
            echo "<script>alert('Error updating password.'); window.location.href = 'forgetpass.php';</script>";
        }
    } else {
        // No user found with matching username and email
        echo "<script>alert('No user found.'); window.location.href = 'forgetpass.php';</script>";
    }

    // Close statement
    $stmt->close();
} else {
    echo "<script>alert('Please fill all details.'); window.location.href = 'forgetpass.php';</script>";
}

// Close connection
$conn->close();

// Functions
function generateRandomSixDigitNumber() {
    return mt_rand(100000, 999999);
}

function sendTacEmail($email, $resetpass, $username) {
    require 'C:/xampp/htdocs/site/PHPMailer-6.9.1/src/Exception.php';
    require 'C:/xampp/htdocs/site/PHPMailer-6.9.1/src/PHPMailer.php';
    require 'C:/xampp/htdocs/site/PHPMailer-6.9.1/src/SMTP.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true); // Use PHPMailer namespace
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPAuth = true;
        $mail->Username = 'fakhrulhakimy93@gmail.com';
        $mail->Password = 'jcouifynxpmymcuw'; // Use your generated App Password here
        $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted

        // Set email details (from, to, subject, message, etc.)
        $mail->setFrom('fakhrulhakimy93@gmail.com', 'Fakhrul');
        $mail->addAddress($email, $username);

        $mail->isHTML(true);
        $mail->Subject = 'Your Temp Password';
        $mail->Body    = "Hello $username,<br><br>Your Temp Pass is: <b>$resetpass</b><br><br>Please use this Password to Login to System and Reset the pass Fast as Possible.";

        $mail->send();
        echo 'TAC code has been sent to your email.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
