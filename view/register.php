<?php
session_start();
// Display error message if authentication failed
if (isset($_SESSION['error'])) {
    echo "<h2>Register Result</h2>";
    echo "<p style='color: red;'>".$_SESSION['error']."</p>";
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
</head>

<body>

    <h2>Register</h2>
    <form action="../controller/UserController.php" method="post" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="imagen">Image:</label> 
        <input type="file" id="image" name="image" size="30" >
        </br>
        <button type="submit" name="register">Register</button>
    </form>
</body>
</html>