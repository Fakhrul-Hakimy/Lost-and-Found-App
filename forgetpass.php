<?php

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
                    <ion-card-title>Forget Password</ion-card-title>
                </ion-card-header>

                <ion-card-content>
                    <div class="greeting">Welcome! Please Enter detail below to reset password.</div>
                    <form action="resetpass.php" method="POST">
                        <ion-input label="Email" label-placement="floating" fill="outline" placeholder="Enter Email" type="email" name="Email" id="Email" required></ion-input>

                        <ion-input label="Username" label-placement="floating" fill="outline" placeholder="Enter Username" type="text" name="username" id="username" required></ion-input>

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


