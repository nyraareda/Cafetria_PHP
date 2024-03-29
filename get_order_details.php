<?php
// Include database connection
include 'dbconnection.php';

// Initialize variables
$orders = array();
$error_message = "";

// Check if session is active
session_start();

// Connect to the database
$db = new db();
$connection = $db->get_connection();

// Check connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if start and end dates are provided
    if (isset($_POST['start_date']) && isset($_POST['end_date']) && !empty($_POST['start_date']) && !empty($_POST['end_date'])) {
        // Sanitize input
        $start_date = validate_data($_POST['start_date']);
        $end_date = validate_data($_POST['end_date']);
        
        // Prepare and execute SQL query to fetch orders within the specified date range for the current user
        $query = "SELECT o.id, o.order_date, SUM(p.product_price * od.quantity) AS total_price, o.order_status, od.quantity, p.product_name, p.product_image, od.product_id
        FROM orders o
        LEFT JOIN orderdetails od ON o.id = od.order_id
        LEFT JOIN products p ON od.product_id = p.id
        WHERE o.order_date BETWEEN ? AND ? AND o.user_id = ?
        GROUP BY o.id, o.order_date, o.order_status, od.quantity, p.product_name, p.product_image, od.product_id";
        
        $statement = $connection->prepare($query);
        $statement->bind_param("ssi", $start_date, $end_date, $_SESSION['id']);
        $statement->execute();
        $result = $statement->get_result();

        if ($result->num_rows > 0) {
            // Fetch and store the orders
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }
        } else {
            $error_message = "No orders found within the specified date range.";
        }
    } else {
        $error_message = "Please provide both start and end dates.";
    }
} else {
    // If form is not submitted, fetch all orders for the current user
    $query = "SELECT o.id, o.order_date, o.order_status, od.quantity, p.product_name, p.product_image, od.product_id
    FROM orders o
    LEFT JOIN orderdetails od ON o.id = od.order_id
    LEFT JOIN products p ON od.product_id = p.id
    WHERE o.user_id = ?";
    
    $statement = $connection->prepare($query);
    $statement->bind_param("i", $_SESSION['id']);
    $statement->execute();
    $result = $statement->get_result();
    $orders = $result->fetch_all(MYSQLI_ASSOC);
    // var_dump($result);
}
// var_dump($_SESSION['id']);
// Function to validate input data
function validate_data($data)
{
    $data = trim($data);
    $data = addslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if cancel order request is made
if (isset($_GET['cancel_order']) && isset($_GET['order_id'])) {
    $order_id = filter_var($_GET['order_id'], FILTER_SANITIZE_NUMBER_INT);
    $delete_order_query = "DELETE FROM orders WHERE id = ? AND user_id = ?";
    $delete_order_statement = $connection->prepare($delete_order_query);
    $delete_order_statement->bind_param('ii', $order_id, $_SESSION['id']);

    if ($delete_order_statement->execute()) {
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Failed to cancel the order. Please try again.";
    }
}

// Close connection
$connection->close();
?>
