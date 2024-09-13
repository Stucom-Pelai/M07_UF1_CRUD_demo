<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Website</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background-color: #007BFF;
            color: #fff;
            padding: 1em;
            text-align: center;
        }

        section {
            padding: 2em;
            text-align: center;
        }

        footer {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 1em;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>

    <header>
        <h1>Welcome to Our Website</h1>
        <p>Your go-to destination for amazing content!</p>
    </header>
    <?php
    if (isset($_SESSION["logged"])) {
        if ($_SESSION["logged"]) {
            // get image from server
            $image =  $_SESSION["image"];
            echo ("<section><center><img style='width: 150px;' src='../view/img/$image' alt='profile image'></center>"); ?>
            <h2>My Profile</h2>
            <h3>this is your profile.</h3>
            <form action="profile.php">
                <input type="submit" value="Profile" />
            </form>
            <form action="../controller/UserController.php" method="post">
                <br>
                <button type="submit" name="logout">Logout</button>
            </form>
            </section>;

    <?php    }
    }
    ?>

    <section>
        <h2>About Us</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin vehicula, libero id fermentum dapibus.</p>
    </section>

    <section>
        <h2>Services</h2>
        <p>We offer a wide range of services to meet your needs.</p>
    </section>

    <section>
        <h2>Contact Us</h2>
        <p>Feel free to reach out to us if you have any questions or inquiries.</p>
    </section>

    <footer>
        &copy; 2023 Our Website. All rights reserved.
    </footer>


</body>

</html>