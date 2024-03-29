<?php
include 'dbconnection.php';

$db = new db();

$conn = $db->get_connection();

if ($conn->connect_error) {
    die ("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $product_name = $_POST["product_name"];
    $product_price = $_POST["product_price"];
    $category_id = $_POST["category"]; // Get the selected category ID
    $is_active = isset ($_POST["is_active"]) ? 1 : 0;
    $target_file = null;
    $product_name = empty ($product_name) ? "NULL" : $product_name;
    $product_price = empty ($product_price) ? "NULL" : $product_price;

    if ($_FILES["product_image"]["name"] !== '') {
        $target_file = basename($_FILES["product_image"]["name"]);
        $uploadOk = 1;
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
        $allowed_formats = array("jpg", "jpeg", "png", "gif");
        $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($file_extension, $allowed_formats)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                $update_result = $db->update_data('Products', $id, $product_name, $product_price, $category_id, $target_file, $is_active);
                if ($update_result === TRUE) {
                    echo "Product updated successfully.";
                } else {
                    echo "Error updating product: " . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        $update_result = $db->update_data('Products', $id, $product_name, $product_price, $category_id, $target_file, $is_active);
        if ($update_result === TRUE) {
            echo "Product updated successfully.";
        } else {
            echo "Error updating product: " . $conn->error;
        }
    }
}

header("Location: allProducts.php");
?>