<?php
session_start(); // Start the session

include 'dbconnection.php';

$database = new db();

$mysqli = $database->get_connection();

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['reset_user_id'])) {
    // Redirect to the login page or display an error message
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_SESSION['reset_user_id']; // Get user ID from session
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password === $confirm_password) {
        $sql = "UPDATE Users SET user_password = ? WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind parameters and execute the statement
            $stmt->bind_param("si", $password, $id);
            if ($stmt->execute()) {
                echo "<div class='alert alert-success' role='alert'>Password updated successfully.</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Error updating password: " . $stmt->error . "</div>";
            }
            $stmt->close();
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $mysqli->error . "</div>";
        }
     } else {
        echo "<div class='alert alert-danger' role='alert'>Error: Password and confirmation do not match.</div>";
    }
}

// Retrieve user's email from the database
$email = '';
$id = $_SESSION['reset_user_id'] ?? null; // Get user ID from session
if ($id) {
    $sql = "SELECT user_email FROM Users WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/styleForm.css">
    <style>
        .input-group-password {
            position: relative;
            color: black;
        }
        .input-group-password .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: black;
        }
        .input-group-password input[type="password"] {
            padding-right: 40px; 
            color: black;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 style="color:white">Update Password</h2>
                <p style="color:white">Please update your password for email: <?php echo $email; ?></p>
                <form id="updatePasswordForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="form-group email-group">
                        <label style="color:white" for="password">New Password:</label>
                        <div class="input-group input-group-password">
                            <input type="password" class="form-control" id="password" name="password" >
                            <div class="input-group-append password-toggle" id="togglePassword">
                                <i class="fas fa-eye" style="color:black"   ></i>
                            </div>
                        </div>
                        <small class="text-danger" id="passwordError"></small>
                    </div>
                    <div class="form-group email-group">
                        <label style="color:white" for="confirm_password">Confirm Password:</label>
                        <div class="input-group input-group-password">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" >
                            <div class="input-group-append password-toggle" id="toggleConfirmPassword">
                                <i class="fas fa-eye" style="color:black"></i>
                            </div>
                        </div>
                        <small class="text-danger" id="confirmPasswordError"></small>
                    </div>
                    <button type="submit" class="btn btn-primary green-btn">Update</button>
                </form>
                <div id="message"></div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Optional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <script>
        document.getElementById('updatePasswordForm').addEventListener('submit', function(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;

            var passwordError = document.getElementById('passwordError');
            var confirmPasswordError = document.getElementById('confirmPasswordError');

            if (password.length < 8) {
                passwordError.textContent = 'Password should be at least 8 characters long.';
                event.preventDefault();
            } else {
                passwordError.textContent = '';
            }

            if (password !== confirmPassword) {
                confirmPasswordError.textContent = 'Password and confirmation do not match.';
                event.preventDefault();
            } else {
                confirmPasswordError.textContent = '';
            }
        });

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            var passwordInput = document.getElementById('password');
            var passwordFieldType = passwordInput.getAttribute('type');
            if (passwordFieldType === 'password') {
                passwordInput.setAttribute('type', 'text');
                this.innerHTML = '<i class="fas fa-eye-slash"></i>'; // تغيير الرمز عند النقر للإخفاء
            } else {
                passwordInput.setAttribute('type', 'password');
                this.innerHTML = '<i class="fas fa-eye"></i>'; // تغيير الرمز عند النقر للإظهار
            }
        });

        // Toggle confirm password visibility
        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            var confirmPasswordInput = document.getElementById('confirm_password');
            var confirmPasswordFieldType = confirmPasswordInput.getAttribute('type');
            if (confirmPasswordFieldType === 'password') {
                confirmPasswordInput.setAttribute('type', 'text');
                this.innerHTML = '<i class="fas fa-eye-slash"></i>'; // تغيير الرمز عند النقر للإخفاء
            } else {
                confirmPasswordInput.setAttribute('type', 'password');
                this.innerHTML = '<i class="fas fa-eye"></i>'; // تغيير الرمز عند النقر للإظهار
            }
        });
    </script>
</body>
</html>
