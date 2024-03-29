<?php
include 'dbconnection.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["deliver"])) {
        $order_id = $_POST["order_id"];
        $db = new db();
        $connection = $db->get_connection();
        $update_query = "UPDATE orders SET order_status = 'out for delivery' WHERE id = $order_id";
        if ($connection->query($update_query) === TRUE) {
            $connection->close();
            header("Location: orders.php");
            exit();
        } else {
            echo "Error updating record: " . $connection->error;
        }
    } elseif (isset($_POST["done"])) { 
        $order_id = $_POST["order_id"];

        $db = new db();
        $connection = $db->get_connection();

        $update_query = "UPDATE orders SET order_status = 'Done' WHERE id = $order_id";

        if ($connection->query($update_query) === TRUE) {
            $connection->close();
            header("Location: orders.php");
            exit();
        } else {
            echo "Error updating record: " . $connection->error;
        }
    }
}
?>