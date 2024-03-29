<?php
include 'get_order_details.php'; 

$db = new db();
$connection = $db->get_connection();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Website</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Your custom CSS -->
    <link rel="stylesheet" href="css.css">
</head>
<body> 


<nav class="navbar navbar-expand-lg  navbar-dark fixed-top">
                <div class="container">
                <img src="img/<?php echo $_SESSION['user_image']?>" alt="img" style="width: 3rem;border-radius: 4rem;height: 3rem;">
                    <a class="navbar-brand" href="#"><?php echo $_SESSION['user_name']?></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                 <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                <a class="nav-link" href="user.php">Home <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="chec.php">My Orders</a>
                            </li>
                            
                        </ul>
                    </div> 
                </div>
        </nav>
<main class="my-orders">
    <section class="main-padding">
        <div class="container">
            <h1>My Orders</h1>
            <form id="order-form" action="" method="post">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="start_date">Date from:</label>
                            <input type="date" class="form-control start"  id="start_date" name="start_date" />
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="end_date">Date to:</label>
                            <input type="date" class="form-control end" id="end_date" name="end_date" />
                            <?php if(isset($error_message)) echo "<p class='text-danger'>$error_message</p>"; ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <input type="submit" name="submit" value="Check" class="p-2 m-2" />
                    </div>
                </div>
            </form>
        </div>
    </section>

    <section class="main-padding">
        <div class="container">
            <div class="admin-orders">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Order Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include 'functions.php'; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Display product images -->
    <section class="main-padding">
        <div class="container product-images">
            <?php
            if (isset($orders) && is_array($orders)) {
                $displayed_products = array();
                $products_in_row = 0;

                foreach ($orders as $order) {
                    if (!in_array($order['product_id'], $displayed_products)) {
                        $displayed_products[] = $order['product_id'];

                        if ($products_in_row == 4) {
                            echo "</div><div class='product-images'>";
                            $products_in_row = 0;
                        }
  
                        echo "<div class='product-image'>";
                        echo "<img src='img/" . $order['product_image'] . "' alt='" . $order['product_name'] . "' class='product-thumbnail'>";
                        echo "<p>" . $order['product_name'] . "</p>";
                        echo "</div>";

                        $products_in_row++;
                    }
                }
            }
            ?>
        </div>
    </section>

    <!-- Display total amount -->

    <section class="main-padding">
    <div class="container">
        <div class="total-price"> 
            <h3>Total Amount</h3>
    <?php
    $total_price = 0;
    $displayed_orders = array();

    if (isset($orders) && is_array($orders)) {
        foreach ($orders as $order) {
            if (!in_array($order['id'], $displayed_orders)) {
                $total_price_query = "SELECT SUM(p.product_price * od.quantity) AS total_price 
                                    FROM orderdetails od 
                                    JOIN products p ON od.product_id = p.id 
                                    WHERE od.order_id = ?";
                
                $statement = $connection->prepare($total_price_query);
                $statement->bind_param('i', $order['id']);
                $statement->execute();
                $result = $statement->get_result();
                
                $total_price_row = $result->fetch_assoc();
                $total_price += $total_price_row['total_price'];
                
                $displayed_orders[] = $order['id'];
            }
        }
    }
?>
            <h3>EGP <span><?php echo number_format($total_price, 2); ?></span></h3>
        </div>
    </div>
</section>


</main>

<footer class="footer">
    <div class="container text-center">
        <span>&copy; 2024 Your Company</span>
    </div>
</footer>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

<?php include 'modalproduct_order.php'; ?>




<script>
    $(document).ready(function() {
        // Function to handle the click event of "View Details" link
        $('.view-details-btn').on('click', function() {
            // Extract the order ID from the data attribute
            var orderId = $(this).data('order-id');
            // Form the modal ID using the order ID
            var modalId = "#orderDetailsModal" + orderId;
            // Show the modal with the corresponding ID
            $(modalId).modal('show');
        });

        // Function to handle the click event of "Cancel Order" link
        $('.cancel-order-btn').on('click', function() {
            var orderId = $(this).data('order-id');
            var modalId = "#cancelConfirmationModal" + orderId;
            $(modalId).modal('show');
        });

        // Function to handle the scroll event for the navbar
        window.onscroll = function() {
            scrollFunction()
        };

        function scrollFunction() {
            if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
                document.querySelector(".navbar").classList.add("navbar-scrolled");
            } else {
                document.querySelector(".navbar").classList.remove("navbar-scrolled");
            }
        }
    });
</script>



</body>
</html>
