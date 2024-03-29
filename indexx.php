
<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styleIndex.css">
</head>
<style>
   </style>
<body>
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
            <!--  -->
            <div class="checks container">
                <div class="des">
                    <h1>checks</h1> <a href="#section2" class="section2">orders</a>
                    <a href="#section1">users</a>
                    <a href="#section3" class="sec3">orders details</a>
                </div>
                <div class="datecheck">
                    <form method="GET">
                        <div class="form-group">
                            <label for="fromDate">From:</label>
                            <input type="date" class="form-control" id="fromDate" name="fromDate" placeholder="Select start date">
                        </div>
                        <div class="form-group">
                            <label for="toDate">To:</label>
                            <input type="date" class="form-control" id="toDate" name="toDate" placeholder="Select end date">
                        </div>

                        <?php
                        include 'dbconnection.php';
                        $db = new db();
                        // Create an instance of the database class
                        $result = $db->get_connection()->query("SELECT * FROM users WHERE role = 'user'");

                        // Check if there are any users
                        if ($result->num_rows > 0) {
                            // Start the select dropdown menu
                            echo '<select class="form-select" aria-label="Default select example" name="userId">';
                            echo '<option selected disabled>Choose user</option>';

                            // Fetch each user's data and create an option tag for their name
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['user_name'] . '</option>';
                            }

                            // Close the select dropdown menu
                            echo '</select>';
                        } else {
                            echo 'No users found.';
                        }
                        ?>
                        <button type="submit" class="btn ">Submit</button>
                    </form>
                    <!--  -->
                    <div class="imgs">
                        <div class="card frist">
                            <img src="./img/macha.jpg" alt="Image 1">
                        </div>
                        <div class="card behind">
                            <img src="./img/macha.jpg" alt="Image 2">
                        </div>
                    </div>
                    <!--  -->
                </div>
                <section >
<h2>users </h2>
    <div id="section1">
    <?php
    // Check if fromDate and toDate are set in the $_GET array
    if (isset($_GET['fromDate']) && isset($_GET['toDate'])) {
        // Retrieve start date and end date from the form
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        
        // Create an instance of the database class
        $db = new db();
        
        // Initialize the user condition
        $userCondition = "";
        
        // Check if userId is set
        if (isset($_GET['userId'])) {
            $userId = $_GET['userId'];
            // Construct the user condition
            $userCondition = "AND u.id = $userId";
        }
        
        // Construct the SQL query to fetch the sum of total prices for all orders within the specified date range
        if (!empty($fromDate) && !empty($toDate)) {
            // Construct the SQL query to fetch the sum of total prices
       // Construct the SQL query to fetch the sum of total prices for users who are not admins
$query = "SELECT u.user_name, SUM(od.quantity * p.product_price) AS total_price 
FROM users u 
INNER JOIN orders o ON u.id = o.user_id
INNER JOIN orderdetails od ON o.id = od.order_id
INNER JOIN products p ON od.product_id = p.id
WHERE o.order_date BETWEEN '$fromDate' AND '$toDate'
AND u.role != 'admin'  -- Exclude admin users
$userCondition
GROUP BY u.user_name";

            
            // Execute the query
            $result = $db->get_connection()->query($query);
        
            // Process the result...
        } else {
            echo "Please select valid start and end dates.";
        }
        
        
        // Display the user's name and total price
        if ($result->num_rows > 0) {
            echo '<table class="table">';
            echo '<thead>';
            echo '<tr><th>User Name</th><th>Total Price</th></tr>';
            echo '</thead>';
            echo '<tbody>';
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row["user_name"] . '</td>';
                echo '<td>' . $row["total_price"] . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No order details found within the specified time period.</p>';
        }
    } else {
        echo '<p >Please select start date and end date.</p>';
    }
    ?>
    </div>
</section>

<section>
    <h2>Orders</h2>
    <div id="section2">
        <?php
        if (isset($_GET['fromDate']) && isset($_GET['toDate'])) {
            $fromDate = $_GET['fromDate'];
            $toDate = $_GET['toDate'];

            $db = new db(); // Assuming you have a database connection setup

            // Query to fetch orders within the specified date range
            $query = "SELECT o.id AS order_id, o.order_date, od.product_id, p.product_name, od.quantity, (od.quantity * p.product_price) AS total_price
                      FROM orders o 
                      INNER JOIN orderdetails od ON o.id = od.order_id
                      INNER JOIN products p ON od.product_id = p.id
                      WHERE o.order_date BETWEEN '$fromDate' AND '$toDate'";

            if (!empty($userId)) {
                $query .= " AND o.user_id = $userId";
            }

            $result = $db->get_connection()->query($query);

            if ($result->num_rows > 0) {
                // Begin table markup
                echo '<table class="table">';
                echo '<thead>';
                echo '<tr><th>Order ID</th><th>Order Date</th><th>Product Name</th><th>Quantity</th><th>Total Price</th></tr>';
                echo '</thead>';
                echo '<tbody>';
                // Output data rows
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $row['order_id'] . '</td>';
                    echo '<td>' . $row['order_date'] . '</td>';
                    echo '<td>' . $row['product_name'] . '</td>';
                    echo '<td>' . $row['quantity'] . '</td>';
                    echo '<td>' . $row['total_price'] . '</td>';
                    echo '</tr>';
                }
                // End table markup
                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p>No orders found for the selected user within the specified time period.</p>';
            }
        } else {
            echo '<p>Please select start and end dates.</p>';
        }
        ?>
    </div>
</section>

<!-- section 3 -->
<section >
<h2>orders details</h2>
    <div id="section3">
    <?php
    // Check if fromDate, toDate, and userId are set in the $_GET array
    if (isset($_GET['fromDate']) && isset($_GET['toDate']) && isset($_GET['userId'])) {
        // Retrieve start date, end date, and user ID from the form
        $fromDate = $_GET['fromDate'];
        $toDate = $_GET['toDate'];
        $userId = $_GET['userId'];
        
        // Create an instance of the database class
        $db = new db();
        
        // Construct the SQL query to fetch product details and order quantity for the specified user and date range
// Construct the SQL query to fetch product details and order quantity for the specified user and date range
$query = "SELECT p.product_image, p.product_name, p.product_price, od.quantity 
          FROM orderdetails od
          INNER JOIN products p ON od.product_id = p.id
          INNER JOIN orders o ON od.order_id = o.id
          WHERE o.order_date BETWEEN '$fromDate' AND '$toDate' AND o.user_id = $userId";


        // Execute the query
        $result = $db->get_connection()->query($query);

        // Display the order details
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="card card_item">';
                echo '<img src="img/' . $row["product_image"] . '" class="card-img-top" alt="Product Image">';
                echo '<div class="card-body">';
                echo '<ul class="list-group">';
                echo '<li class="list-group-item">Name: ' . $row["product_name"] . '</li>';
                echo '<li class="list-group-item">Price: $' . $row["product_price"] . '</li>';
                echo '<li class="list-group-item">Quantity: ' . $row["quantity"] . '</li>';
                echo '</ul>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No order details found.</p>';
        }
    } else {
        echo '<p>Please select start date, end date, and user ID.</p>';
    }
    ?>

</div>
</section> 

        </div>
    </div>
    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <span>&copy; 2024 Your Company</span>
        </div>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    window.onload = function () {
        $(document).ready(function () {
            $("a").on('click', function (event) {
                if (this.hash !== "") {
                    event.preventDefault();
                    var hash = this.hash;
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 800, function () {
                        window.location.hash = hash;
                    });
                }
            });
        });

        window.onscroll = function () {
            scrollFunction()
        };
        function scrollFunction() {
            if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                document.querySelector("nav").classList.add("navbar-scrolled");
            } else {
                document.querySelector("nav").classList.remove("navbar-scrolled");
            }
        }
    }

   
</script>



</body>

</html>