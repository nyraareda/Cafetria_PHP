<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <link rel="stylesheet" href="css/addUser.css">

</head>

<body>
<?php

$errors=[];
if(isset($_GET['errors'])){
    $errors=json_decode($_GET['errors'],true);

}


?>
    <div id="smooth-wrapper container">
        <div id="smooth-content">
            <!--  -->
            <nav class="navbar navbar-expand-lg  navbar-dark fixed-top">
                <div class="container">
                <img src="img/<?php session_start(); echo $_SESSION['user_image']?>" alt="img" style="width: 3rem;border-radius: 4rem;height: 3rem;">
                    <a class="navbar-brand" href="#"><?php echo $_SESSION['user_name']?></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item active">
                                <a class="nav-link" href="admin.php">Home <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="allProducts.php">product</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="allUsers.php">users</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="orders.php">manual orders</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="indexx.php">checks</a>
                            </li>
                        </ul>
                    </div>
                </div>
        </nav>
            <div class="container py-5">
    <form action="add.php" method="POST" enctype="multipart/form-data" id="registrationForm" class="py-5">
        <div class="mb-3">
            <label for="nameField" class="form-label">Name</label>
            <input type="text" class="form-control" name="user_name" id="nameField" aria-describedby="nameHelp">
            <small class="error-message">Invalid name (at least 8 characters)</small>
            <?php
            if (isset($errors['user_name'])) {
                echo $errors['user_name'];
            }
            ?>
        </div>

        <div class="mb-3">
    <label for="emailField" class="form-label">Email address</label>
    <input type="email" class="form-control" name="user_email" id="emailField" aria-describedby="emailHelp">
    <?php if (isset($errors['user_email'])): ?>
        <small class="text-danger"><?php echo $errors['user_email']; ?></small>
    <?php endif; ?>
    <small class="error-message">Invalid email address</small>
</div>

        <div class="mb-3">
            <label for="passwordField" class="form-label">Password</label>
            <input type="password" class="form-control" name="user_password" id="passwordField">
            <small class="error-message">Invalid password (at least 8 characters)</small>
            <?php
            if (isset($errors['user_password'])) {
                echo $errors['user_password'];
            }
            ?>
        </div>

        <div class="mb-3">
            <label for="confirmpasswordField" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="confirm_password" id="confirmpasswordField">
            <small class="error-message">Passwords do not match</small>
        </div>

        <div class="mb-3">
    <label for="roomnumberField" class="form-label">Room Number</label>
    <input type="text" class="form-control" name="room_number" id="roomnumberField">
    <?php if (isset($errors['room_number'])): ?>
        <small class="text-danger"><?php echo $errors['room_number']; ?></small>
    <?php endif; ?>
    <small class="error-message">Invalid room number (at least 3 characters)</small>
</div>

<div class="mb-3">
    <label for="extnumberField" class="form-label">Extension Number</label>
    <input type="text" class="form-control" name="ext_number" id="extnumberField">
    <?php if (isset($errors['ext_number'])): ?>
        <small class="text-danger"><?php echo $errors['ext_number']; ?></small>
    <?php endif; ?>
    <small class="error-message">Invalid extension number (at least 3 characters)</small>
</div>

        <div class="mb-3">
            <label for="imageField" class="form-label">Upload Profile Picture</label>
            <input type="file" class="form-control" name="user_image" id="imageField">
            <small class="error-message">Please select an image.</small>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script  src="js/addUser.js"> </script>
   
   
  
    
   </body>
   </html>