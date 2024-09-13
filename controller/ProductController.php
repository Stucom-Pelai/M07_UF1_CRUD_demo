<?php
session_start();

// check if form is submitted
$product = new ProductController();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // check button
    if (isset($_POST["add"])) {
        echo "<p>Add button is clicked.</p>";
        $product->add();
    }
    if (isset($_POST["edit"])) {
        echo "<p>Edit button is clicked.</p>";
        $product->edit();
    }
    if (isset($_POST["update"])) {
        echo "<p>Update button is clicked.</p>";
        $product->update();
    }
    if (isset($_POST["delete"])) {
        echo "<p>Delete button is clicked.</p>";
        $product->delete();
    }
    if (isset($_POST["total"])) {
        echo "<p>Total button is clicked.</p>";
        $product->calculateTotal();
    }
}

class ProductController
{
    private $conn;

    public function __construct()
    {
        // database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dam1m05uf3p1";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    /**
     * Add product to list
     */
    public function add(): void
    {
        // get form data
        $name = $_POST['name'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        // verify if values are numeric
        if (is_numeric($quantity) && is_numeric($price)) {
            // if list empty added
            if (empty($_SESSION['list'])) {

                $_SESSION['list'][$_SESSION['index']] = array("name" => $name, "quantity" => $quantity, "price" => $price);

                // array_push($_SESSION['list'], array(
                //      "name" => $name,
                //      "quantity" => $quantity,
                //      "price" => $price
                //  ));

                $_SESSION['index']++;
                echo '<pre>';
                var_dump($_SESSION['list']);
                echo '</pre>';
                $message = "Item added properly.";
                //var_dump($_SESSION);

            } else {
                // check if item is included
                $isIncluded = FALSE;
                foreach ($_SESSION['list'] as $item) {
                    if ($item['name'] == $name) {
                        # code...
                        $isIncluded = TRUE;
                        break;
                    }
                }
                // if item is not in list, added
                if (!$isIncluded) {
                    $_SESSION['list'][$_SESSION['index']] = array("name" => $name, "quantity" => $quantity, "price" => $price);
                    $_SESSION['index']++;
                    // array_push($_SESSION['list'], array(
                    //     "name" => $name,
                    //     "quantity" => $quantity,
                    //     "price" => $price
                    // ));
                    $message = "Item added properly.";
                    echo '<pre>';
                    var_dump($_SESSION['list']);
                    echo '</pre>';
                } else {
                    // show error
                    $error = "Item already in list, try to edit it.";
                }
            }
        } else {
            $error = "Quantity and price must be numeric.";
        }
    }

    /**
     * Edit product to list
     */
    public function edit(): void
    {
        $name = $_POST['name'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $index = $_POST['index'];
        if (is_numeric($quantity) && is_numeric($price)) {
            // $_SESSION['list'][$index] = array(
            //     "name" => $name,
            //     "quantity" => $quantity,
            //     "price" => $price
            // );
            $message = "Item selected properly.";
        } else {
            $error = "Quantity and price must be numeric.";
        }
    }

    /**
     * Update product to list
     */
    public function update(): void
    {
        $name = $_POST['name'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $index = $_POST['index'];
        if (is_numeric($quantity) && is_numeric($price)) {
            $_SESSION['list'][$index] = array(
                "name" => $name,
                "quantity" => $quantity,
                "price" => $price
            );
            $message = "Item updated properly.";
        } else {
            $error = "Quantity and price must be numeric.";
        }
    }

    /**
     * Delete product from list
     */
    public function delete(): void
    {
        $index = $_POST['index'];

        //array_splice($_SESSION['list'], $index, 1);
        unset($_SESSION['list'][$index]);

        $message = "Item deleted properly.";
    }

    /**
     * Calculate total shopping list
     */
    public function calculateTotal(): float
    {

        $totalValue = 0.0;
        foreach ($_SESSION['list'] as $item) {
            $totalValue += $item['quantity'] * $item['price'];
        }
        return $totalValue;
    }
}
