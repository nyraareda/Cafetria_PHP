<?php
session_start(); 
require_once 'dbconnection.php'; 

$db = new db();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['productName'])) {
       
        $productName = $_POST['productName'];
        $productCheckResult = $db->get_data("Products WHERE product_name = '$productName'");

        if ($productCheckResult->num_rows > 0) {
         
            echo 'exists';
        } else {
          
            echo 'not_exists';
        }
    } elseif (isset($_POST['categoryName'])) {
        $categoryName = $_POST['categoryName'];
        $categoryCheckResult = $db->get_data("Categories WHERE category_name = '$categoryName'");

        if ($categoryCheckResult->num_rows > 0) {
            echo 'exists';
        } else {
            $insertResult = $db->insert_data("Categories", ["category_name"], ["'$categoryName'"]);

            if ($insertResult === TRUE) {
                echo 'added';
            } else {
                echo 'error';
            }
        }
    }
}
?>
