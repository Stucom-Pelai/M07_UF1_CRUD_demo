<?php
session_start();
// Display error message if authentication failed
if (isset($_SESSION['error'])) {
    echo "<h2>Edit Result</h2>";
    echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
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

        .profile-container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        img {
            max-width: 100%;
            border-radius: 50%;
        }
    </style>
</head>

<body>

    <header>
        <a href="home.php" class="home-button">&#127968; Home</a>
        <h1>Edit User Profile</h1>
    </header>

    <section class="profile-container">
        <img src="../view/img/profile-picture.png" alt="User Profile Picture">
        <br>
        <br>
        <form action="../controller/UserController.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value=<?php echo $_SESSION['userName'] ?> required>
            <br>
            <br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value=<?php echo $_SESSION['userName'] ?> required>
            <br>
            <br>
            <label for="mail">Email:</label>
            <input type="email" id="email" name="email" value=<?=  $_SESSION['userEmail'] ?> required>
            <br>
            <br>
            <!-- Add more profile details as needed -->
            <button type="submit" name="update">Update</button>
        </form>
        <form action="../controller/UserController.php" method="post">            
            <br>
            <br>
            <!-- Add more profile details as needed -->
            <button type="submit" name="delete">Delete</button>
            <br>
            <br>
        </form>
    </section>

    <footer>
        &copy; 2023 User Profile Page. All rights reserved.
    </footer>

</body>

</html>