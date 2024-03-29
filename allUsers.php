<!doctype html>
<html>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <title>all Users</title>
    <!-- <script src="/js/createProducts.js"></script> -->
    <link rel="stylesheet" href="./css/admin_style.css">

</head>
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
        background-repeat: no-repeat;
        background-image:
            linear-gradient(to bottom, rgba(245, 246, 252, 0.3), rgba(94, 141, 112, 0.3)),
            url('img/33.jpeg');
        background-size: cover;
        color: rgb(62, 62, 62);
        padding: 20px;
        
    }

    .btncontainer {
        display: flex;
        justify-content: space-evenly;
        align-items: baseline;
        margin: 0.5rem 0 auto;
        height: 2rem;
    }

    .no-room,
    .no-ext {
        color: red !important;
    }
    #main{

        height: 100vh;
    }
</style>

<body>
    <div class="container"  id="main" >
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
        <div class="container" id="allproduct">
            <div>
                <ul id="ulheader">
                    <li>
                        <h3 id="h3header">All Users</h3>
                    </li>
                    <a id="add" href="addUser.php" target="_blank" rel="noopener noreferrer">
                        <li>Add User</li>
                    </a>
                </ul>
            </div>
            <div class="container">
                 
                <div class="row" id="cardcontainer">
                    <?php
                    include 'dbconnection.php';
                    $db = new db();
                    $result = $db->get_data('Users WHERE role = "user"');


                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="card" id="containercard">';
                            echo '<img src="img/' . $row["user_image"] . '" class="card-img-top imgcard" alt="User Image">';
                            echo '<div class="card-body" id="containercard-body">';
                            echo '<h5 class="card-title container-card-title">' . $row["user_name"] . '</h5>';
                            echo '<ul class="list-group" id="containerlist-group">';
                            echo '<li class="list-group-item containerlist-group-item">Email: ' . $row["user_email"] . '</li>';
                            // echo '<li class="list-group-item containerlist-group-item">Role: ' . $row["role"] . '</li>';
                    
                            echo '<li class="list-group-item containerlist-group-item"' . ($row["room_number"] ? '' : ' style="color: red !important;"') . '>Room Number: ' . ($row["room_number"] ? $row["room_number"] : 'Not in Room') . '</li>';
                            echo '<li class="list-group-item containerlist-group-item"' . ($row["ext_number"] ? '' : ' style="color: red !important;"') . '>Ext Number: ' . ($row["ext_number"] ? $row["ext_number"] : 'Not have Ext Number') . '</li>';
                            echo '</ul>';

                            echo '<div class="btncontainer">';
                            echo '<form action="edit_user.php" method="post">';
                            echo '<input type="hidden" name="id" value="' . $row["id"] . '">';
                            echo '<button type="submit" class="btn btn-primary">Edit</button>';
                            echo '</form>';
                            // Form to submit the delete action
                            echo '<form id="deleteForm-' . $row["id"] . '" action="delete_user.php" method="post">';
                            echo '<input type="hidden" name="id" value="' . $row["id"] . '">';
                            echo '<button type="button" class="btn btn-danger" onclick="showConfirmation(' . $row["id"] . ')">Delete</button>';
                            echo '</form>';


                            // Bootstrap Modal for confirmation
                            echo '<div class="modal" id="confirmationModal' . $row["id"] . '">';
                            echo '<div class="modal-dialog">';
                            echo '<div class="modal-content">';
                            echo '<div class="modal-header">';
                            echo '<h5 class="modal-title" style="margin-right: 20rem;">Delete!</h5>';
                            echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                            echo '</div>';
                            echo '<div class="modal-body">';
                            echo 'Are you sure you want to delete this item?';
                            echo '</div>';
                            echo '<div class="modal-footer">';
                            echo '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>';
                            echo '<button type="button" class="btn btn-danger" onclick="submitForm(' . $row["id"] . ')">Delete</button>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>'; // btncontainer
                            echo '</div>'; // card-body
                            echo '</div>'; // card
                    
                        }
                    } else {
                        echo '<p>No users found.</p>';
                    }
                    ?>
                </div> <!-- End of row for user cards -->
            </div> <!-- End of container -->

        </div> <!-- End of container for cards -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        function showConfirmation(id) {
            var modal = new bootstrap.Modal(document.getElementById('confirmationModal' + id), {
                keyboard: false
            });
            modal.show();
        }

        function submitForm(id) {
            document.getElementById('deleteForm-' + id).submit();
        }


        function scrollFunction() {
            if (
                document.body.scrollTop > 5 ||
                document.documentElement.scrollTop > 5
            ) {
                document.querySelector("nav").classList.add("navbar-scrolled");
            } else {
                document.querySelector("nav").classList.remove("navbar-scrolled");
            }
        }
        window.addEventListener("scroll", scrollFunction);

    </script>
</body>

</html>