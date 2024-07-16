<?php
session_start(); // Start the session

// Check if the user is logged in by checking the session variable
if (!isset($_SESSION['username'])) {
    // If the session variable is not set, redirect to the login page
    echo "<script>alert('No session Found Please Login first.'); window.location.href = 'index.html';</script>";
}

// If the session variable is set, proceed with the rest of the page
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Lost Item</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />
</head>

<body>
    <h1>Lost And Found System</h1>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <!-- Rest of the main page content -->

    <ion-button color="primary">
        <a href="addnewitem.php" style="text-decoration: none; color: inherit;">
            Add Item
        </a>
    </ion-button>
    <ion-button>Delete Item</ion-button><br>
    <ion-button color="warning">Update Item List</ion-button><br>
    <ion-button color="secondary">Search Item</ion-button><br>
    <ion-button color="tertiary">View Item List</ion-button><br>
    <ion-button color="success">
        <a href="changepass.php" style="text-decoration: none; color: inherit;">
            Change Password
        </a>
    </ion-button>
    <form action="logout.php" method="post">
        <ion-button type="submit" color="danger">Logout</ion-button>
    </form>




    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


</body>

</html>