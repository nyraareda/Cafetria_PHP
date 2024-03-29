<?php
include 'dbconnection.php';

$db = new db();
$conn = $db->get_connection();

if ($conn->connect_error) {
    die ("Connection failed: " . $conn->connect_error);
}

if (isset ($_POST['id'])) {
    $id = $_POST['id'];

    // Get the room number and extension number of the user
    $user_data = $db->get_user_data('Users', "id = $id");
    if ($user_data->num_rows > 0) {
        $row = $user_data->fetch_assoc();
        $room_number = $row['room_number'];
        $ext_number = $row['ext_number'];

        // Delete the user
        $result_user = $db->del_data('Users', $id);

        // Check if the user deletion was successful
        if ($result_user === TRUE) {
            // Delete the room if it exists
            if ($room_number !== null && $ext_number !== null) {
                $result_room = $db->user_del_data('Rooms', "room_number = $room_number AND ext_number = $ext_number");
                if ($result_room === TRUE) {
                    echo "User and associated room deleted successfully";
                } else {
                    echo "Error deleting associated room: " . $conn->error;
                }
            } else {
                echo "User deleted successfully";
            }
        } else {
            echo "Error deleting user: " . $conn->error;
        }
    } else {
        echo "User not found";
    }
}

header("Location: allUsers.php");
exit;
?>