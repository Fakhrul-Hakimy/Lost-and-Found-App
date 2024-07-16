<?php
session_start(); // Start the session

// Check if the user is logged in by checking the session variable
if (!isset($_SESSION['username'])) {
    // If the session variable is not set, redirect to the login page
    header("Location: http://localhost/site/login.php");
    exit(); // Ensure that subsequent code is not executed
}

// If the session variable is set, proceed with the rest of the page
?>

<!DOCTYPE html>
<html>

<head>
    <title>Lost And Found System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />
</head>

<body>
    <h1>Lost And Found System</h1>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <!-- Rest of the main page content -->
    <ion-button>Default</ion-button>
    <ion-button color="primary">Primary</ion-button>
    <ion-button color="secondary">Secondary</ion-button>
    <ion-button color="tertiary">Tertiary</ion-button>
    <ion-button color="success">Success</ion-button>
    <ion-button color="warning">Warning</ion-button>
    <ion-button color="danger">Danger</ion-button>
    <ion-button color="light">Light</ion-button>
    <ion-button color="medium">Medium</ion-button>
    <ion-button color="dark">Dark</ion-button>

    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>

</body>

</html>