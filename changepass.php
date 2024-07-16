<?php
session_start(); // Start the session

// Check if the user is logged in by checking the session variable
if (!isset($_SESSION['username'])) {
    // If the session variable is not set, redirect to the login page
    echo "<script>alert('No session Found Please Login first.'); window.location.href = 'index.html';</script>";
}

?>

<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />
</head>

<body>
    
    <ion-app>
        <ion-content class="ion-padding">
            <ion-card>
                <ion-card-header>
                    <ion-card-title>Change Password</ion-card-title>
                </ion-card-header>

                <ion-card-content>
                    <div class="greeting">Welcome! Please Enter detail below to change password.</div>
                    <form action="processChangepass.php" method="POST">
                        <ion-input label="Current Password" label-placement="floating" fill="outline" placeholder="Enter Current Password" type="password" name="oldpass" id="oldpass" required></ion-input>

                        <ion-input label="New Password" label-placement="floating" fill="outline" placeholder="Enter New Password" type="password" name="newpass" id="newpass" required></ion-input>

                        <ion-button expand="block" type="submit">Register</ion-button>
                    </form> 
                </ion-card-content>
            </ion-card>
        </ion-content>
    </ion-app>
    
    


    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


</body>



</html>


