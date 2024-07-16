<?php
session_start(); // Start the session

// Check if the user is logged in by checking the session variable
if (!isset($_SESSION['username'])) {
    // If the session variable is not set, redirect to the login page
    echo "<script>alert('No session Found. Please login first.'); window.location.href = 'index.html';</script>";
}

// If the session variable is set, proceed with the rest of the page
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost And Found System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 70px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        ion-button {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 1px auto;
            padding: 10px;
            text-align: center;
        }
        .buttons-container {
            text-align: center;
        }
        a {
            text-decoration: none;
            color: inherit;
        }
        form {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Lost And Found System</h1>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        
        <div class="buttons-container">
            <ion-button color="primary">
                <a href="addnewitem.php">Add Item</a>
            </ion-button>
            <ion-button color="danger">
                <a href="deleteitem.php">Delete Item</a>
            </ion-button>
            <ion-button color="warning">
                <a href="updateitemlist.php">Update Item List</a>
            </ion-button>
            <ion-button color="secondary">
                <a href="searchitem.php">Search Item</a>
            </ion-button>
            <ion-button color="tertiary">
                <a href="viewitemlist.php">View Item List</a>
            </ion-button>
            <ion-button color="success">
                <a href="changepass.php">Change Password</a>
            </ion-button>
        </div>
        
        <form action="logout.php" method="post">
            <ion-button type="submit" color="danger">Logout</ion-button>
        </form>
    </div>

    <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script>
    <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>
