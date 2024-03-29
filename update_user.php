<?php
include 'dbconnection.php';

$db = new db();

$conn = $db->get_connection();

if ($conn->connect_error) {
    die ("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $user_name = $_POST["user_name"];
    $user_email = $_POST["user_email"];
    $room_number = $_POST["room_number"];
    $ext_number = $_POST["ext_number"];

    $errors = [];

    // Check if the provided room number is different from the original and if it already exists
    if ($room_number != $original_room_number) {
        $existingRoom = $db->get_data("Users", "room_number = '$room_number' AND id != $id");
        if ($existingRoom->num_rows > 0) {
            $errors['room_number'] = "This room number is already in use. Please choose a different one.";
        }
    }

    // Check if the provided extension number is different from the original and if it already exists
    if ($ext_number != $original_ext_number) {
        $existingExtNumber = $db->get_data("Users", "ext_number = '$ext_number' AND id != $id");
        if ($existingExtNumber->num_rows > 0) {
            $errors['ext_number'] = "This extension number is already in use. Please choose a different one.";
        }
    }

    // If there are errors, redirect back to the edit form page with error messages
    if (!empty ($errors)) {
        $errors = json_encode($errors);
        header("Location: edit_user.php?id=$id&errors=" . urlencode($errors));
        exit;
    }

    // If no errors, update the user data
    $update_result = $db->update_user_data($id, $user_name, $user_email, $room_number, $ext_number);
    if ($update_result === TRUE) {
        echo "User updated successfully.";
    } else {
        echo "Error updating user: " . $conn->error;
    }
}

header("Location: allUsers.php");
?>