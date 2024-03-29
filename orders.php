<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: var(--light);
            background-color: var(--dark);
            padding-top: 80px;
            background-image:
            linear-gradient(to bottom, rgba(245, 246, 252, 0.718), rgba(94, 141, 112, 0.73)),
            url('img/back.jpg');
             background-size: cover;
             color: rgb(62, 62, 62);
            background-position: -2px -2px, -2px -2px, -1px -1px, -1px -1px;
            height: 100vh;
            position: relative;
            padding-bottom: 70px;
            background-repeat: no-repeat;
        }
        .total-price {
            border-bottom: 5px solid black;
            font-weight: bold;
            font-style: italic;
            font-size: 3em;
            margin-top: 10px;
        } 

        .product-image {
            width: 300rem; 
            height: 300rem;
        }

        .product-price-display {
            display: none;
            position: absolute;
            background-color: white;
            padding: 5px;
            border: 1px solid #ccc;
            z-index: 1;
        }

        .image-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; 
            justify-content: center;
        }

        .image-item {
            flex: 0 0 auto;
        }

        .product-details {
            display: none;
            position:relative; 
            background-color: white;
            padding: 5px;
            border: 1px solid #ccc;
            z-index: 1;
            text-align: center;
        }

        .product-image-container:hover .product-details {
            display: block;
        }
                #main{

              height: 100vh;
              }
            </style> 
</head>
<body>
<div id="smooth-wrapper container" id= "main">
        <div id="smooth-content">
            <!--  -->
            <nav class="navbar navbar-expand-lg  navbar-dark fixed-top">
                <div class="container">
                <img src="img/<?php session_start(); echo $_SESSION['user_image']?>" alt="img" style="width: 3rem;border-radius: 4rem;height: 3rem;">
                    <a class="navbar-brand" href="#"><?php echo $_SESSION['user_name']?></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                 <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                <li class="nav-item">
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

    <main class="admin-orders">
        <section class="main-padding">
            <div class="container">
                <h1>Orders</h1>
                <table class="table">
                    <thead class="thead-light">
                        <tr>
                            <th>Order</th>
                            <th>Order Date</th>
                            <th>Name</th>
                            <th>Room</th>
                            <th>Ext</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        include 'dbconnection.php'; 

                        $db = new db();
                        $connection = $db->get_connection();

                        $orders_query = "SELECT orders.*, users.user_name 
                                        FROM orders 
                                        JOIN users ON orders.user_id = users.id 
                                        WHERE orders.order_status = 'Processing' OR orders.order_status = 'out for delivery'"; // Fetch orders with status 'Processing' or 'Delivered'
                        $orders_result = $connection->query($orders_query);

                        // Check for errors
                        if (!$orders_result) {
                            echo "Error: " . $connection->error;
                            exit;
                        }
                        $row = $orders_result->fetch_assoc();
                        
                        // Display orders and their details
                        $row_count = 0; 
                        while ($row = $orders_result->fetch_assoc()) { 
                            $row_count++;
                            $row_color = ($row_count % 2 == 0) ? 'even-color' : 'odd-color';
                        ?>

                        <tr id="orderRow_<?php echo $row['id']; ?>" class="<?php echo $row_color; ?>">
                            <td><?php echo "Order: " . $row_count; ?></td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td><?php echo $row['user_name']; ?></td>
                            <td><?php echo $row['room_number']; ?></td>
                            <td><?php echo $row['ext_number']; ?></td>
                            <td><?php echo $row['order_status']; ?></td>
                            <td>
                                <!-- Action buttons -->
                                <form id="orderForm_<?php echo $row['id']; ?>" action="allorders.php" method="post">
                                    <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                    <?php if ($row['order_status'] == 'processing') { ?>
                                        <button type="submit" name="deliver" class="btn btn-dark">Deliver</button>
                                    <?php } ?>
                                    <?php if ($row['order_status'] == 'out for delivery') { ?>
                                        <button type="submit" name="done" class="btn btn-success">Done</button>
                                    <?php } ?>
                                </form>
                            </td>
                        </tr>
                        <tr class="<?php echo $row_color; ?>">
        <!-- Second row for images -->
        <td colspan="7" class="image-row">
            <?php
            $order_id = $row['id'];
            $order_details_query = "SELECT od.quantity, p.product_name, p.product_image, p.product_price 
                                    FROM orderdetails od 
                                    JOIN products p ON od.product_id = p.id 
                                    WHERE od.order_id = $order_id";
            $order_details_result = $connection->query($order_details_query);
            $images_html = "<div class='image-container'>";
            while ($detail = $order_details_result->fetch_assoc()) {
                // Accumulate images and details HTML
                $images_html .= "<div class='image-item'>";
                $images_html .= "<div class='product-image-container'>";
                $images_html .= "<img src='img/" . $detail['product_image'] . "' alt='" . $detail['product_name'] . "' class='product-image'>";
                $images_html .= "<div class='product-details'>";
                $images_html .= "<p>Product: " . $detail['product_name'] . "</p>";
                $images_html .= "<p>Quantity: " . $detail['quantity'] . "</p>";
                $images_html .= "<p>Price: EGP " . $detail['product_price'] . "</p>";
                $images_html .= "</div>";
                $images_html .= "</div>";
                $images_html .= "</div>";
            }
            $images_html .= "</div>";
            echo $images_html;
            ?>
        </td>
    </tr>

    <tr class="<?php echo $row_color; ?>">
        <!-- Third row for total price -->
        <td colspan="7" class="total-price">
            <?php
            $total_price_query = "SELECT SUM(p.product_price * od.quantity) AS total_price 
                                  FROM orderdetails od 
                                  JOIN products p ON od.product_id = p.id 
                                  WHERE od.order_id = $order_id";
            $total_price_result = $connection->query($total_price_query);
            $total_price_row = $total_price_result->fetch_assoc();
            $total_price = $total_price_row['total_price'];
            echo "Total Price: EGP " . $total_price;
            ?>
        </td>
    </tr>
                        <?php
                        } // End of while loop for orders
                        ?>

                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <footer class="footer mt-auto py-3">
        <div class="container text-center">
            <span>&copy; 2024 Your Company</span>
        </div>
    </footer>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
    </script>
</body>
</html>
