<?php
include 'dbconnection.php';

$db = new db();

$conn = $db->get_connection();

if ($conn->connect_error) {
    die ("Connection failed: " . $conn->connect_error);
}

$user_name = "";
$user_email = ""; // Initialize user_email
$room_number = ""; // Initialize room_number
$ext_number = ""; // Initialize ext_number

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    $result = $db->get_data("Users WHERE id = $id");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_name = $row["user_name"];
        $user_email = $row["user_email"];
        $role = $row["role"];
        $room_number = $row["room_number"];
        $ext_number = $row["ext_number"];
    }
}

// Check if there are errors from previous form submissions
if (isset ($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true);
}
?>


<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="css/styleProduct.css"> -->
    <style>
        :root {
            --dark: #1d1d1d;
            --grey-dark: #414141;
            --light: #fff;
            --mid: #ededed;
            --grey: #989898;
            --gray: #989898;
            --green: #28a92b;
            --green-dark: #4e9815;
            --green-light: #6fb936;
            --blue: #2c7ad2;
            --purple: #8d3dae;
            --red: #c82736;
            --orange: #e77614;
            accent-color: var(--green);
        }

        body {
            background-color: #111;
            font-family: "Signika Negative", sans-serif, Arial;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            color: white;
            display: flex;
            justify-content: center;
            /* Center horizontally */
            align-items: center;
            /* Center vertically */
            height: 100vh;
            /* Full viewport height */
        }

        #mainwrapper {
            width: 100%;
        }

        #wrapper {
            position: relative;
            overflow: visible;
            /* width: 100vw; */
            height: 100%;
            background-image:
                linear-gradient(rgba(255, 255, 255, .07) 2px, transparent 2px),
                linear-gradient(90deg, rgba(255, 255, 255, .07) 2px, transparent 2px),
                linear-gradient(rgba(255, 255, 255, .06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .06) 1px, transparent 1px);
            background-size: 100px 100px, 100px 100px, 20px 20px, 20px 20px;
            background-position: -2px -2px, -2px -2px, -1px -1px, -1px -1px;
        }


        .forform {
            max-width: 35rem;
            /* Max width of the form */
            padding: 2rem;
            border: 1px solid #6fb936;
            /* Add border for visibility */
            border-radius: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            /* Semi-transparent background */
            display: flex;
            /* Use flexbox */
            flex-direction: column;
            /* Arrange children in a column */
            justify-content: center;
            /* Center vertically */
            align-items: center;
            /* Center horizontally */
            margin: auto;
            /* Center horizontally */
            margin-top: 2rem;
            margin-bottom: 3rem;
            /* Add top margin */
        }

        /* Additional styles for input and select */
        .input {
            color: white;
            background-color: transparent;
            width: 100%;
        }

        .input:focus {
            color: white;
            background-color: transparent;
        }

        .error-message {
            display: none;
            /* Hide error messages by default */
            color: #dc3545;
        }

        #footer {
            width: 100%;
            background-color: #6fb936;
            /* position: absolute; */
            bottom: 0;
            left: 0;
        }

        .updatebtn {
            background-color: #6fb936;
        }
    </style>
</head>
<?php

$errors = [];
if (isset ($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true);

}


?>

<body>
    <div id="mainwrapper">
        <div id="wrapper">
            <div class="forform">
                <h2>Edit User</h2>

                <form action="update_user.php" method="post" enctype="multipart/form-data"
                    onsubmit="return validateForm()">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="mb-3">
                        <label for="user_name" class="form-label">User Name:</label>
                        <input type="text" class="form-control input" id="user_name" name="user_name"
                            value="<?php echo isset ($_POST['user_name']) ? htmlspecialchars($_POST['user_name']) : $user_name; ?>">
                        <small id="name_error" class="error-message" style="display: none;">Enter valid Name (at least 4
                            characters)</small>
                    </div>
                    <div class="mb-3">
                        <label for="user_email" class="form-label">Email:</label>
                        <input type="email" class="form-control input" id="user_email" name="user_email"
                            value="<?php echo isset ($_POST['user_email']) ? htmlspecialchars($_POST['user_email']) : $user_email; ?>">
                        <small id="email_error" class="error-message" style="display: none;">Please Enter valid
                            Email</small>
                    </div>
                    <div class="mb-3">
                        <label for="room_number" class="form-label">Room Number:</label>
                        <input type="text" class="form-control input" id="room_number" name="room_number"
                            value="<?php echo isset ($_POST['room_number']) ? htmlspecialchars($_POST['room_number']) : $room_number; ?>"
                            pattern="[1-9][0-9]{2,}" title="Room number must contain at least 3 digits starting from 1">
                        <?php if (isset ($errors['room_number'])): ?>
                            <small class="text-danger">
                                <?php echo $errors['room_number']; ?>
                            </small>
                        <?php endif; ?>
                        <small id="room_error" class="error-message" style="display: none;">Invalid room number (at
                            least 3
                            digits starting from 1)</small>
                    </div>
                    <div class="mb-3">
                        <label for="ext_number" class="form-label">Extension Number:</label>
                        <input type="text" class="form-control input" id="ext_number" name="ext_number"
                            value="<?php echo isset ($_POST['ext_number']) ? htmlspecialchars($_POST['ext_number']) : $ext_number; ?>"
                            pattern="[1-9][0-9]{2,}"
                            title="Extension number must contain at least 3 digits starting from 1">
                        <?php if (isset ($errors['ext_number'])): ?>
                            <small class="text-danger">
                                <?php echo $errors['ext_number']; ?>
                            </small>
                        <?php endif; ?>
                        <small id="ext_error" class="error-message" style="display: none;">Invalid extension number (at
                            least 3 digits starting from 1)</small>
                    </div>
                    <div class="mb-3">
                        <label for="user_image" class="form-label">Image:</label>
                        <input type="file" class="form-control input" id="user_image" name="user_image">
                        <?php if (isset ($errors['image'])): ?>
                            <small class="text-danger">
                                <?php echo $errors['image']; ?>
                            </small>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary updatebtn">Update User</button>
                    <a href="allUsers.php" class="btn btn-secondary">Cancel</a>
                </form>

            </div>

            <footer class="footer mt-auto py-3" id="footer">
                <div class="container text-center">
                    <span>&copy; 2024 Your Company</span>
                </div>
            </footer>
        </div>
    </div>

    <script>
        var originalValues = {
            user_name: "<?php echo $user_name; ?>",
            user_email: "<?php echo $user_email; ?>",
            room_number: "<?php echo $room_number; ?>",
            ext_number: "<?php echo $ext_number; ?>",
        };

        function validateForm() {
            var name = document.getElementById("user_name").value;
            var email = document.getElementById("user_email").value;
            var roomNumber = document.getElementById("room_number").value;
            var extNumber = document.getElementById("ext_number").value;
            var image = document.getElementById("user_image").value;

            var isValid = true;

            // Validate name if changed
            if (name !== originalValues.user_name && name.length < 3) {
                document.getElementById("name_error").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("name_error").style.display = "none";
            }

            // Validate email if changed
            if (email !== originalValues.user_email && !validateEmail(email)) {
                document.getElementById("email_error").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("email_error").style.display = "none";
            }

            // Validate room number if changed
            if (roomNumber !== originalValues.room_number && roomNumber.trim() === "") {
                document.getElementById("room_error").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("room_error").style.display = "none";
            }

            // Validate extension number if changed
            if (extNumber !== originalValues.ext_number && extNumber.trim() === "") {
                document.getElementById("ext_error").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("ext_error").style.display = "none";
            }

            // Validate image if changed
            if (image !== "") {
                // Check if a file is selected
                var fileInput = document.getElementById('user_image');
                if (fileInput.files.length === 0) {
                    // No file selected, hide the error message and continue
                    document.getElementById("image_error").style.display = "none";
                } else {
                    // File selected, validate the file extension or other criteria if needed
                    // You can add additional validation for the file here if necessary
                }
            }

            return isValid;
        }

        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }

        function validateRoomNumber() {
            var roomNumberInput = document.getElementById("room_number");
            var roomError = document.getElementById("room_error");

            // Check if the input is empty
            if (roomNumberInput.value.trim() === "") {
                // If it's empty, display the error message
                roomError.style.display = "block";
            } else {
                // Otherwise, hide the error message
                roomError.style.display = "none";
            }
        }

        function validateExtNumber() {
            var extNumberInput = document.getElementById("ext_number");
            var extError = document.getElementById("ext_error");

            // Check if the input is empty
            if (extNumberInput.value.trim() === "") {
                // If it's empty, display the error message
                extError.style.display = "block";
            } else {
                // Otherwise, hide the error message
                extError.style.display = "none";
            }
        }

        document.getElementById("room_number").addEventListener("blur", validateRoomNumber);
        document.getElementById("ext_number").addEventListener("blur", validateExtNumber);

    </script>
</body>

</html>