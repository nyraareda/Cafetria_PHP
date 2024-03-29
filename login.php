<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
background-color: var(--dark);
padding-top: 80px;
background-image: 
linear-gradient(rgba(255, 255, 255, 0.07) 2px, transparent 2px),
linear-gradient(90deg, rgba(255, 255, 255, 0.07) 2px, transparent 2px),
linear-gradient(rgba(255, 255, 255, 0.06) 1px, transparent 1px),
linear-gradient(90deg, rgba(255, 255, 255, 0.06) 1px, transparent 1px);
background-size: 100px 100px;
height: 100vh;
}

        .container {
            width: 500px;
            height: 400px;
            background: linear-gradient(300deg,var(--grey-dark),var(--dark),var(--grey));
             background-size: 300% 300%;
             animation: gradient-animation 30s ease infinite;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-label,
        a#forgotPasswordLink {
            color: white;
            decoration: none;
        }

        .form-control {
            background-color: transparent;  
            border: none;
            border-bottom: 1px solid white;
            color: white;
            transition: border-bottom 0.3s;
        }
        @keyframes gradient-animation {
    0% {
      background-position: 0% 50%;
    }
    50% {
      background-position: 100% 50%;
    }
    100% {
      background-position: 0% 50%;
    }
  }
        .form-control:hover {
            border-bottom: 1px solid #fff;
        }

        .error-feedback {
            color: red;
            display: none;
        }

        .btn-primary {
            background-color: white;
            color: #795548;
            transition: background-color 0.3s, color 0.3s;
        }

        .btn-primary:hover {
            background-color: #795548;
            color: #fff;
        }

        .error {
            border-bottom: 1px solid red !important;
        }
    </style>
</head>

<body>
<?php

$errors=[];
if(isset($_GET['errors'])){
    $errors=json_decode($_GET['errors'],true);

}


?>
   <div class="container">
        <?php
        if (!empty($errors)) {
            echo '<div class="alert alert-danger" role="alert">';
            foreach ($errors as $error) {
                echo $error . '<br>';
            }
            echo '</div>';
        }
        ?>
        <form action="save.php" method="POST" onsubmit="return validateForm()" novalidate>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" name="user_email" id="exampleInputEmail1" aria-describedby="emailHelp">
                <div class="error-feedback email-error">Invalid email address!</div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" name="user_password" id="exampleInputPassword1">
                <div class="error-feedback password-error">Password must be at least 8 characters!</div>
            </div>
            <div class="mb-3">
                <a href="forgetPassword.php" id="forgotPasswordLink">Forgot Password?</a>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script>
        document.querySelector("#forgotPasswordLink").addEventListener("click", function (event) {
           // event.preventDefault();
            alert("Forgot Password link clicked!");
        });

        function validateForm() {
            var emailInput = document.getElementById("exampleInputEmail1");
            var passwordInput = document.getElementById("exampleInputPassword1");

            var email = emailInput.value;
            var password = passwordInput.value;

            var emailValid = validateEmail(email);
            var passwordValid = validatePassword(password);

            var emailError = document.querySelector('.email-error');
            var passwordError = document.querySelector('.password-error');

            if (!emailValid) {
                emailError.style.display = 'block';
                emailInput.classList.add('error');
                return false;
            } else {
                emailError.style.display = 'none';
                emailInput.classList.remove('error');
            }

            if (!passwordValid) {
                passwordError.style.display = 'block';
                passwordInput.classList.add('error');
                return false;
            } else {
                passwordError.style.display = 'none';
                passwordInput.classList.remove('error');
            }

            return true;
        }

        function validateEmail(email) {
            var re = /(.+)@(.+){2,}\.(.+){2,}/;
            return re.test(email.toLowerCase());
        }

        function validatePassword(password) {
            return password.length >= 8;
        }
    </script>
</body>

</html>