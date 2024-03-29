<?php
session_start();
require 'dbconnection.php';
$db = new db();
echo '<pre>';
var_dump($_POST);
echo '</pre>';
if(($_POST['users'])){
    if(isset($_POST['submit_order']) && !empty($_SESSION['cart'])) {
        $orderData = [
            'quantity' => $_POST['quantity'],
            'product_name' => $_POST['product_name'],
            'notes' => htmlspecialchars($_POST['notes']),
            'room_selection' => $_POST['room_selection'],
            'user_id' => intval($_POST['users']), 
        ];

        $room_value = explode(",", $orderData['room_selection']);
        $orderData['room_number'] = $room_value[0];
        $orderData['ext_number'] = $room_value[1];

        var_dump($orderData);

        $query = "INSERT INTO orders (user_id, notes, room_number, ext_number) VALUES (?, ?, ?, ?)";
        $stmt = $db->get_connection()->prepare($query);
        $stmt->bind_param("isii", $orderData['user_id'], $orderData['notes'], $orderData['room_number'], $orderData['ext_number']);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        foreach($_SESSION['cart'] as $product) {
            $db->insert_data('orderdetails', ['product_id', 'order_id', 'quantity'], [$product['product_id'], $order_id, $product['quantity']]);
        }

        $_SESSION['cart'] = [];
         $user_role = $_SESSION['role'];
        $user_role = 'admin';
        if($user_role == 'user') {
            header('Location: user.php');
        } else if($user_role == 'admin') {
            header('Location: admin.php');
        }
        exit();
    }

} else { 
    if(isset($_POST['submit_order']) && !empty($_SESSION['cart'])) {
        $orderData = [
            'quantity' => $_POST['quantity'],
            'product_name' => $_POST['product_name'],
            'notes' => htmlspecialchars($_POST['notes']),
            'room_selection' => $_POST['room_selection'],
        ];

        $room_value = explode(",", $orderData['room_selection']);
        $orderData['room_number'] = $room_value[0];
        $orderData['ext_number'] = $room_value[1];

        $user_id = $_SESSION['id']; 
        $user_role = $_SESSION['role'];

        var_dump($user_role);

        $query = "INSERT INTO orders (user_id, notes, room_number, ext_number) VALUES (?, ?, ?, ?)";
        $stmt = $db->get_connection()->prepare($query);
        $stmt->bind_param("isii", $user_id, $orderData['notes'], $orderData['room_number'], $orderData['ext_number']);
        $stmt->execute();
        $order_id = $stmt->insert_id;

        foreach($_SESSION['cart'] as $product) {
            $db->insert_data('orderdetails', ['product_id', 'order_id', 'quantity'], [$product['product_id'], $order_id, $product['quantity']]);
        }

        $_SESSION['cart'] = [];

        if($user_role == 'user') {
            header('Location: user.php');
        } else if($user_role == 'admin') {
            header('Location: admin.php');
        }
        exit();
    }
}
?>


