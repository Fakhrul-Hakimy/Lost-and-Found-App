<?php
session_start(); // Start the session

// Check if the user is logged in by checking the session variable
if (!isset($_SESSION['username'])) {
    // If the session variable is not set, redirect to the login page
    echo "<script>alert('No session Found Please Login first.'); window.location.href = 'index.html';</script>";
}
?>

<html>

<head>
    <title>Add Item</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #c4c3c3ce;
        }

        ion-card {
            width: 100%;
            max-width: 500px; /* Adjust the maximum width of the card as needed */
            text-align: center;
            margin-left: auto;
            margin-right: auto;
            margin-top: 50px;
            margin-bottom: auto;
        }

        ion-card-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        ion-input {
            margin-bottom: 15px;
        }

        ion-button {
            margin-top: 15px;
        }

        .greeting {
            margin-bottom: 15px;
            font-size: 1.2em;
            color: #4a4a4a;
        }

        .forgot-password {
            margin-top: 10px;
            color: #3880ff;
            text-decoration: underline;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <ion-app>
        <ion-content class="ion-padding">
            <ion-card>
                <ion-card-header>
                    <ion-card-title>Add Item</ion-card-title>
                </ion-card-header>

                <ion-card-content>
                    <div class="greeting">Please Fill in All Item Details</div>
                    <form action="processItem.php" method="POST">

                        <ion-input label="Item Name" label-placement="floating" fill="outline"
                            placeholder="Enter Item Name" type="text" name="ItemName" id="ItemName"
                            required></ion-input>

                            <ion-list>
                            <ion-item>
                                <ion-select aria-label="Category" placeholder="Select Category" name="Category">
                                    <ion-select-option value="Electronics">Electronics</ion-select-option>
                                    <ion-select-option value="Clothing">Clothing</ion-select-option>
                                    <ion-select-option value="Accessories">Accessories</ion-select-option>
                                    <ion-select-option value="Documents">Documents</ion-select-option>
                                    <ion-select-option value="Personal Items">Personal Items</ion-select-option>
                                    <ion-select-option value="Sporting Goods">Sporting Goods</ion-select-option>
                                    <ion-select-option value="Toys & Games">Toys & Games</ion-select-option>
                                    <ion-select-option value="Tools">Tools</ion-select-option>
                                    <ion-select-option value="Medical Items">Medical Items</ion-select-option>
                                    <ion-select-option value="Other">Other</ion-select-option>
                                </ion-select>
                            </ion-item>
                        </ion-list>

             

                        <ion-input label="Description" label-placement="floating" fill="outline"
                            placeholder="Enter Description" type="text" name="Description" id="Description"
                            required></ion-input>

                        <ion-datetime label="Date Found" label-placement="floating" name="date" id="date" required></ion-datetime>

                        <ion-input label="Finder's Name" label-placement="floating" fill="outline"
                            placeholder="Enter Finder's Name" type="text" name="FName" id="FName"
                            required></ion-input>

                        <ion-input label="Finder's Contact" label-placement="floating" fill="outline"
                            placeholder="Enter Contact Number" type="text" name="Fnumber" id="Fnumber"
                            required></ion-input>

                        <br>
                        <input type="file" accept="image/*;capture=camera">
                        <br>
                        <ion-button type="submit">Submit</ion-button>
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
