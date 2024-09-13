<?php
session_start();

// check if form is submitted
$user = new UserController();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // check button
    if (isset($_POST["login"])) {
        echo "<p>Login button is clicked.</p>";
        $user->login();
    }
    if (isset($_POST["logout"])) {
        echo "<p>Logout button is clicked.</p>";
        $user->logout();
    }
    if (isset($_POST["register"])) {
        echo "<p>Register button is clicked.</p>";
        $user->register();
    }
    if (isset($_POST["edit"])) {
        echo "<p>Edit button is clicked.</p>";
        $user->edit();
    }
    if (isset($_POST["update"])) {
        echo "<p>Update button is clicked.</p>";
        $user->update();
    }
    if (isset($_POST["delete"])) {
        echo "<p>Delete button is clicked.</p>";
        $user->delete();
    }
}

class UserController
{
    private $conn;

    public function __construct()
    {
        // database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "daw2m07uf1";

        try {
            $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    /**
     * login user to application
     */
    public function login(): void
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        // check against a database
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // $stmt = $this->conn->prepare("SELECT name, password FROM users  WHERE name=? AND password=?");
        // $stmt->bindparam("ss", $username, $password);
        // $stmt->execute();
        //$stmt = $this->conn->prepare("SELECT id,name, password, email, path_img FROM users WHERE name=:name AND password=:password");
        $stmt = $this->conn->prepare("SELECT id,name, password, email, path_img FROM users WHERE name=:name");
        $stmt->bindParam(':name', $username);
        //$stmt->bindParam(':password', $password);

        try {
            //code...
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            // verify password
            if ($user && password_verify($password, $user['password'])) {
                // authentication successful
                $_SESSION['userId'] = $user['id'];
                $_SESSION['userName'] = $user['name'];
                $_SESSION['userPassword'] = $user['password'];
                $_SESSION['userEmail'] = $user['email'];
                $_SESSION['image'] = $user['path_img'];
                $_SESSION['logged'] = true;
                $_SESSION['user'] = $username;

                $this->conn = null;
                // redirect to home page
                header("Location: ../view/profile.php");
                exit();
            } else {
                // authentication failed, display an error message
                $_SESSION["logged"] = false;
                $_SESSION['error'] = "Invalid username or password.";
                $this->conn = null;
                // redirect to login
                header("Location: ../view/login.php");
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->conn = null;
            // redirect to register page
            header("Location: ../view/login.php");
            //throw $th;

        } finally {
            // Close connection
            $this->conn = null;
        }
    }

    /**
     * logout user from application
     */
    public function logout(): void
    {
        // clean variables
        unset($_SESSION["logged"]);
        unset($_SESSION["user"]);
        // destro session
        session_destroy();
        // redirect to index page
        header("Location: ../view/index.php");
    }

    /**
     * register user to application
     */
    public function register(): void
    {
        // get data from form
        $username = $_POST["username"];
        //$password = $_POST["password"];
        // encrypt password
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $email = $_POST["email"];
        $nameImage = $_FILES['image']['name'];
        $typeImage = $_FILES['image']['type'];
        $sizeImage = $_FILES['image']['size'];
        // check image exist and size
        if (!empty($nameImage) && ($sizeImage <= 2000000)) {
            //check format
            if (($typeImage == "image/jpeg")
                || ($typeImage == "image/jpg")
                || ($typeImage == "image/png")
            ) {
                // path to save images
                $target_dir = "../view/img/";
                // define folder + name of file
                $target_file = $target_dir . basename($nameImage);
                // move image from temporal folder to image server folder
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    echo "uploading";
                } else {
                    //in case any error moving to server
                    $_SESSION['error'] = "Error uploading";
                    $_SESSION['error'] = $target_file;
                    // redirect to register page
                    header("Location: ../view/register.php");
                }
            } else {
                //in case any error in format image
                $_SESSION['error'] = "Invalid image format";
                // redirect to register page
                header("Location: ../view/register.php");
            }
        } else {
            //in case error in size image
            if ($nameImage == !NULL) {
                $_SESSION['error'] = "Invalid image size";
                // redirect to register page
                header("Location: ../view/register.php");
                exit();
            }
        }

        // validate data form
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format";
            // redirect to register page
            header("Location: ../view/register.php");
            exit();
        }

        // insert to database

        // set the PDO error mode to exception
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // prepare sql and bind parameters
        // $stmt = $this->conn->prepare("INSERT INTO Users (name, password, email, path_img) VALUES (?, ?, ?, ?)");
        // $stmt->bindparam("ssss", $username, $password, $email, $nameImage);
        $stmt = $this->conn->prepare("INSERT INTO Users (name, password, email, path_img) 
                                      VALUES (:name, :password, :email, :path_img)");
        $stmt->bindParam(':name', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':path_img', $nameImage);

        try {
            //code...
            // use exec() because no results are returned            
            if ($stmt->execute()) {
                echo "New record created successfully";
                // register successful
                $_SESSION["logged"] = true;
                $_SESSION["userName"] = $username;
                $_SESSION["userEmail"] = $email;
                $_SESSION["image"] = $nameImage;
                $this->conn = null;
                // redirect to home page
                header("Location: ../view/home.php");
                exit();
            } else {
                $_SESSION['error'] = "Invalid register";
                $this->conn = null;
                // redirect to register page
                header("Location: ../view/register.php");
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->conn = null;
            // redirect to register page
            header("Location: ../view/register.php");
            //throw $th;

        } finally {
            // Close connection
            $this->conn = null;
        }
    }

    /**
     * edit user data
     */
    public function edit(): void
    {
        // redirect to edit page
        header("Location: ../view/edit.php");
    }

    /**
     * update user data
     */
    public function update(): void
    {
        // get data from form   ; 
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];   

        // validate data form
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Invalid email format";
            // redirect to register page
            header("Location: ../view/edit.php");
            exit();
        }

        // update to database
        // set the PDO error mode to exception
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // prepare sql and bind parameters
        $stmt = $this->conn->prepare("UPDATE Users 
                                      SET name=:name,
                                          password=:password,
                                          email=:email
                                          WHERE id=:id");
        $stmt->bindParam(':name', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $_SESSION['userId']);

        try {
            //code...
            // use exec() because no results are returned            
            if ($stmt->execute()) {
                echo "Profile updated successfully";
                // update successful
                $_SESSION["logged"] = true;
                $_SESSION["userName"] = $username;
                $_SESSION["userEmail"] = $email;
                $_SESSION['message'] = "Profile updated successfully";
                $this->conn = null;
                // redirect to profile page
                header("Location: ../view/profile.php");
                exit();
            } else {
                $_SESSION['error'] = "Invalid update";
                $this->conn = null;
                // redirect to edit page
                header("Location: ../view/edit.php");
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->conn = null;
            // redirect to edit page
            header("Location: ../view/edit.php");
            //throw $th;

        } finally {
            // Close connection
            $this->conn = null;
        }
    }

    /**
     * delete user from application
     */
    public function delete(): void
    {
        // get data from form
        var_dump($_SESSION['userId'])    ; 
        
        // delete from database
        // set the PDO error mode to exception
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // prepare sql and bind parameters
        $stmt = $this->conn->prepare("DELETE FROM Users WHERE id=:id");
        $stmt->bindParam(':id', $_SESSION['userId']);

        try {
            //code...
            // use exec() because no results are returned            
            if ($stmt->execute()) {
                echo "User deleted successfully";
                // delete successful
                $_SESSION["logged"] = false;
                $this->logout();

            } else {
                $_SESSION['error'] = "Invalid delete";
                $this->conn = null;
                // redirect to edit page
                header("Location: ../view/edit.php");
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = $e->getMessage();
            $this->conn = null;
            // redirect to edit page
            header("Location: ../view/edit.php");
            //throw $th;

        } finally {
            // Close connection
            $this->conn = null;
        }
    }
}
