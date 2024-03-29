<?php
include 'dbconnection.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_name = validate_data($_POST['user_name']);
    $user_email = validate_data($_POST['user_email']);
    $user_password = validate_data($_POST['user_password']);
    $room_number = validate_data($_POST['room_number']);
    $ext_number = validate_data($_POST['ext_number']);
    $db = new db();
    $existingUser = $db->get_user_data('Users', "user_email = '$user_email'");
    $existingRoom = $db->get_user_data('Rooms', "room_number = '$room_number'");
    $existingExtNumber = $db->get_user_data('Users', "ext_number = '$ext_number'");

    if ($existingUser->num_rows > 0) {
        $errors['user_email'] = "This email is already in use. Please choose a different one.";
    }

    if ($existingRoom->num_rows > 0) {
        $errors['room_number'] = "This room number is already in use. Please choose a different one.";
    }

    if ($existingExtNumber->num_rows > 0) {
        $errors['ext_number'] = "This extension number is already in use. Please choose a different one.";
    }
    if (!empty($_FILES['user_image']['name'])) {
        $user_image = $_FILES["user_image"]["name"];
        $upload_directory = "images/"; 
        $upload_path = $upload_directory . basename($user_image);
        if (!move_uploaded_file($_FILES["user_image"]["tmp_name"], $upload_path)) {
            $errors['file_upload'] = "Failed to upload file.";
        }
    } else {
        $errors['user_image'] = "Please upload an image.";
    }

    if (empty($errors)) {
        $db->insert_data_user(
            "Rooms",
            ["room_number", "ext_number"],
            ["'$room_number'", "'$ext_number'"]
        );
        $room_id = $db->get_last_insert_id($db->get_connection());

        $db->insert_data_user(
            "Users",
            ["user_name", "user_email", "user_password", "user_image", "room_number", "ext_number"],
            ["'$user_name'", "'$user_email'", "'$user_password'", "'$user_image'", "'$room_number'", "'$ext_number'"]
        );
        $result = $db->get_user_data('Users', "user_email = '$user_email'");
        $user_data = $result->fetch_assoc();
        header("Location: addUser.php");
        exit;
    }else {
        $errors[] = "Invalid email or ext number or room number";
        $errors = json_encode($errors);
        var_dump($errors);
        header("Location: addUser.php?errors=" . urlencode($errors));
        exit;
    }

}

function validate_data($data)
{
    $data = trim($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>