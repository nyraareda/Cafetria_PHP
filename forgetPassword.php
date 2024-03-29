<?php
session_start(); 

include 'dbconnection.php';

$database = new db();

$mysqli = $database->get_connection();

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $sql = "SELECT id FROM Users WHERE user_email = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id);
            $stmt->fetch();
            $stmt->close();

            // Store user ID in session
            $_SESSION['reset_user_id'] = $id;

            header("Location: update_password.php");
            exit();
        } else {
            $error_message = "Your email is not signed up";
        }
    } else {
        $error_message = "Error: " . $mysqli->error;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Email</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styleForm.css">
</head>
<body>
    <div class="container email-form mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form id="checkEmailForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" class="border p-4 rounded">
                    <h2 class="mb-4" style="color:white">Check Email</h2>
                    <div class="form-group">
                        <label style="color:white" for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control">
                        <small class="text-danger" id="emailError"></small>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Check" class="btn btn-primary green-btn">
                    </div>
                </form>
                <?php 
                // Display error message if set
                if (isset($error_message)) {
                    echo "<p class='text-danger'>$error_message</p>";
                }
                ?> 
            </div>
        </div>
    </div>

    <!-- Add Bootstrap JS (Optional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('checkEmailForm').addEventListener('submit', function(event) {
            var email = document.getElementById('email').value;
            var emailError = document.getElementById('emailError');

            var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                emailError.textContent = 'Please enter a valid email address.';
                event.preventDefault();
            } else {
                emailError.textContent = '';
            }
        });
    </script>
</body>
</html>
