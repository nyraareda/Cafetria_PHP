<?php
require 'dbconnection.php';
$db = new db();
session_start();

if(isset($_POST['submit_order'])) {
    $_SESSION['cart'] = $_POST['product_id']; 
    $_SESSION['notes'] = $_POST['notes'];
    $_SESSION['room_selection'] = $_POST['room_selection'];

}
if(isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['product_id'])){
    $product_id = $_GET['product_id'];

    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] === $product_id) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
}

if(isset($_POST['add_to_cart'])){
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_image = $_POST['product_image'];
    $product_price = $_POST['product_price'];

    $cart_item = array(
        'product_id' => $product_id,
        'product_name' => $product_name,
        'product_image' => $product_image,
        'product_price' => $product_price,
        'quantity' => 1 
    );

    if(isset($_SESSION['cart'])){
        $found = false;
        foreach ($_SESSION['cart'] as $key => $item) {
            if ($item['product_id'] === $product_id) {
                $_SESSION['cart'][$key]['quantity'] += 1; 
                $found = true;
                break;
            }
        }
        if (!$found) {
            $_SESSION['cart'][] = $cart_item;
        }
    } else {
        $_SESSION['cart'] = array($cart_item);
    }
}

?>



<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- css  -->
    <link rel="stylesheet" href="css/style.css">
    <!-- fontawsome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>user page</title>
</head>
<body>
     
    <div class="container-fluid">
    <nav class="navbar navbar-expand-lg  navbar-dark fixed-top">
                <div class="container">
                <img src="img/<?php echo $_SESSION['user_image']?>" alt="img" style="width: 3rem;border-radius: 4rem;height: 3rem;">
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
        <div class="row justify-content-center align-items-center">
            <div class="col-sm-12 col-md-12 col-lg-8 p-0">
                <div class="row m-4">
                    <div class="latest-order d-flex flex-row align-items-center gap-4 p-4">   
                        <h1>latest ordered</h1>
                        <?php
                            $sql = "SELECT id FROM orders ORDER BY order_date DESC LIMIT 1";
                            $id_order_result = $db->get_connection()->query($sql);
                            $id_order_row = $id_order_result->fetch_assoc(); 
                            $id_order = $id_order_row['id']; 

                            $product_id_query = "SELECT product_id FROM orderdetails WHERE order_id = $id_order";
                            $product_id_results = $db->get_connection()->query($product_id_query);

                            while ($product_id_row = $product_id_results->fetch_assoc()) {
                                $product_id = $product_id_row['product_id']; 
                                $sqlproduct = "SELECT product_name ,product_image FROM products WHERE id = $product_id";
                                $result = $db->get_connection()->query($sqlproduct);
                                while ($latestOrder = $result->fetch_assoc()) {
                                    echo '<div class="col">';
                                    echo '<div class="card latest-order-card" style="width: 18rem;display: flex;justify-content: center;flex-direction: row;align-items: center; height: 8rem;">';
                                    echo ' <img src="img/'.$latestOrder['product_image'].'" class="img-fluid" alt="...">';
                                    echo '<div class="card-body latest-order-card-body d-flex justify-content-start align-items-end">';
                                    echo '<h5 class="card-title text-center">'.$latestOrder['product_name'].'</h5>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        <!-- end-latest-ordered-card -->
                    </div>
                </div>
                <div class="row m-2">
                    <div class="container">
                        <div class="row gx-0">
                           <div class="product-section" id="product-section">
                                <div class="row justify-content-between flex-wrap row-product gx-0">
                                    <div class="row justify-content-center align-items-center gx-0 ">                                  
                                          <h1 class="text-center "style="width: 21rem;">Products</h1>
                                         <input class="form-control me-2" style="width: 21rem;" type="search" name="product-search" id="product_search" placeholder="Search" aria-label="Search">
                                    </div>

                                    <?php
                                        $result = $db->get_data('products');
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<div class="col-sm-12 col-md-6 col-lg-4 my-4  p-0 product-col">';
                                            echo '<div class="card card-main-product m-auto border-0">';
                                            echo ' <div class="card-content">';
                                            echo '     <div class="product-price">';
                                            echo '         <span><sup>e£</sup>' . $row['product_price'] . '</span>';
                                            echo "        <div class='card-content-image'>
                                                        <img src='img/" . $row['product_image'] . "'>
                                                    </div>";
                                            echo '     </div>';
                                            echo '     <div class="card-content-details d-flex  flex-column  align-items-center">';
                                            echo '         <div class="product-info px-2">';
                                            echo '             <h6 class="text-center fw-bold m-0">' . $row['product_name'] . '</h6>';
                                            echo '         </div>';
                                            echo '         <div class="product-action d-flex justify-content-between align-items-center">';
                                            echo '             <form  method="POST">';
                                            echo '             <input type="hidden" value="' . $row['id'] . '" name="product_id" />';
                                            echo '             <input type="hidden" value="' . $row['product_name'] . '" name="product_name" />';
                                            echo '             <input type = "hidden" value="' . $row['product_image'] . '" name="product_image" />';
                                            echo '             <input type = "hidden" value="' . $row['product_price'] . '" name="product_price" />';
                                            echo '             <button type="submit" name="add_to_cart" class="btn my-3 add-to-cart-btn">Add to cart</button>';                                           
                                             echo '          </div>';
                                            echo '             </form>';
                                            echo '     </div>';
                                            echo ' </div>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-8 col-lg-4 ">
                <div class="cart-section d-flex flex-column">
                    
                    </div>
                    <div class="cart d-flex flex-column justify-content-around flex-wrap ">
                        <h1 class="text-center">check out cart</h1>
                        <div class="cart-items" id="cart-items">
                        <form  id="orderForm"method="POST" action="ordered.php">
                        <div class="user_selection d-flex justify-content-center align-items-center my-5">
                        <h4 class="text-center">Select User</h4>
                    <?php
                            $user_query = " SELECT  * FROM users";
                            $user_results = $db->get_connection()->query($user_query);
                            if ($user_results->num_rows > 0) {
                                echo '<select name="users" class="m-3">';
                                $user_row = $user_results->fetch_assoc();
                             
                                while ($user_row = $user_results->fetch_assoc()) {
                                    
                                         echo '<option value="' . $user_row['id'] . '">' . $user_row['user_name'] .'</option>';
                                     }
                            
                                 echo '</select>';
                                
                            } else {
                                echo '<p>No Users found</p>';
                            }
                           echo '</div>';
                          
                        if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
                            foreach($_SESSION['cart'] as $key => $item){
                                echo '<div class="card cart-card d-flex justify-content-around flex-row  gap-1 align-items-center mb-2">';
                                echo '    <div class="cart-img d-flex flex-column align-items-center  m-2 ">';
                                echo '      <input type= "hidden" value='.$_SESSION['id'].'/>';
                                echo '       <input type="hidden" name="product_id[]" value="' . $item['product_id'] . '">';
                                echo '       <input type="hidden" name="product_name[]" value="' . $item['product_name'] . '">';
                                echo '          <span class="text-center" >'.$item['product_name'].'</span>';
                                echo '        <img src="img/' . $item['product_image'] . '" alt="' . $item['product_name'] . '">';
                                echo '    </div>';
                                echo '    <div class="cart-quantity d-flex gap-1">';
                                echo '        <button type="button" class="btn decrease-btn" onclick="decreaseQuantity(' . $key . ',' . $item['product_price'] . ')">-</button>';
                                echo '        <input name="quantity[]" id="quantity_' . $key . '" type="number" min="1" max="20" class="form-control" value="' . $item['quantity'] . '" readonly>';
                                echo '        <button type="button" class="btn increase-btn" onclick="increaseQuantity(' . $key . ',' . $item['product_price'] . ')">+</button>';
                                echo '    </div>';
                                echo '    <div class="cart-price">';
                                echo '        <span class="fw-bolder">$' . $item['product_price'] . '</span>';
                                echo '    </div>';
                                echo '    <div class="cart-close">';
                                echo '            <button type="button" class="btn close-btn bg-danger text-light" onclick="removeFromCart(' . $item['product_id'] . ')"> Remove </button>';
                                 echo '    </div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p class="text-center">Your cart is empty.</p>';
                        }
                      ?>
                        </div>
                        <div class="cart-notes mb-2">
                            <div class="form-floating">
                                <textarea class="form-control" placeholder="" name="notes" id="floatingTextarea2" style="height: 100px ;border: 1px solid var(--dark);" oninput="validateNotes()"></textarea>
                                <label for="floatingTextarea2">Notes</label>
                                <p id="notesError" style="color:red;"></p>

                            </div>
                        </div>
                        <div class="cart-room mb-2">
                            <span class="fw-bolder">Room</span>
                            <?php
                            $room_query = " SELECT  * FROM rooms";
                            $room_results = $db->get_connection()->query($room_query);
                            if ($room_results->num_rows > 0) {
                                echo '<select name="room_selection" class="m-3">';
                                
                                while ($room_row = $room_results->fetch_assoc()) {
                                    $room_number = $room_row['room_number'];
                                    $ext_number = $room_row['ext_number'];
                                    echo '<option value="' . $room_number . ',' . $ext_number . '">' . $room_number . ' - Ext: ' . $ext_number . '</option>';
                                }
                            
                                echo '</select>';
                                
                            } else {
                                echo '<p>No rooms found</p>';
                            }
                           
                            ?>
                           
                        </div>
                        <div class="cart-total-price mb-2">
                            <h3>Total Price: $<span id="total-price" name="total_price"><?php echo $totalPrice; ?></span></h3>
                        </div>
                        <div class="cart-submit mb-2 text-center">
                            <button type="submit" id="submitOrderBtn"  name="submit_order" class="btn w-75">Submit Order</button>
                        </div>
                    </form>


                        
                    </div>
                </div>
            </div>        
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/js/all.min.js" integrity="sha512-GWzVrcGlo0TxTRvz9ttioyYJ+Wwk9Ck0G81D+eO63BaqHaJ3YZX9wuqjwgfcV/MrB2PhaVX9DkYVhbFpStnqpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
    
        document.addEventListener('DOMContentLoaded', function() {
                let totalPrice = 0.0;
                <?php
                if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])){
                    foreach($_SESSION['cart'] as $key => $item){
                        echo 'totalPrice += ' . $item['product_price'] . ' * ' . $item['quantity'] . ';';
                    }
                }else{
                    echo 'totalPrice = 0.0;';
                }
                
                ?>
                const totalPriceSpan = document.getElementById('total-price');
                totalPriceSpan.innerText = totalPrice.toFixed(2);
            });
           function updateTotalPrice(key, price) {
                const quantityInput = document.getElementById('quantity_' + key);
                const totalPriceSpan = document.getElementById('total-price');
                const totalPriceInput = document.getElementById('total-price-input');
                let total = 0.0;

                 <?php
                 foreach($_SESSION['cart'] as $key => $item){
                     echo 'if (' . $key . ' != key) {';
                     echo '    total += ' . $item['product_price'] . ' * document.getElementById("quantity_' . $key . '").value;';
                     echo '}';
                 }
                 ?>

                total += price * quantityInput.value;
                totalPriceSpan.innerText = total.toFixed(2); 
                totalPriceInput.value = total.toFixed(2); 
                preventSubmitIfTotalZero();
            }

    const orderForm = document.getElementById('orderForm');
    const submitOrderBtn = document.getElementById('submitOrderBtn');

    orderForm.addEventListener('submit', function(event) {
        if (<?php echo empty($_SESSION['cart']) ? 'true' : 'false'; ?>) {
            event.preventDefault();
            alert('Your cart is empty. Add items before submitting.');
        }
    });


    function preventSubmitIfTotalZero() {
    let totalPrice = document.getElementById('total-price').innerText;
    let submitButton = document.querySelector('button[name="submit_order"]');
    if (totalPrice === '0') {
        submitButton.disabled = true; 
    } else {
        submitButton.disabled = false;
    }
}

    function decreaseQuantity(key, price) {
        const input = document.getElementById('quantity_' + key);
        let value = parseInt(input.value, 10);
        value = isNaN(value) ? 1 : value;
        if (value > 1) {
            value--;
            input.value = value;
            updateTotalPrice(key, price);
        }
    }

    function increaseQuantity(key, price) {
        const input = document.getElementById('quantity_' + key);
        let value = parseInt(input.value, 10);
        value = isNaN(value) ? 1 : value;
        value++;
        input.value = value;
        updateTotalPrice(key, price);
    }
   
            
 
 
    function validateNotes() {
        var notes = document.getElementById('floatingTextarea2').value;
        var error = document.getElementById('notesError');
        var sqlKeywords = ['SELECT', 'UPDATE', 'DELETE', 'INSERT', 'DROP', 'ALTER', 'CREATE', 'TRUNCATE',
                   'select', 'update', 'delete', 'insert', 'drop', 'alter', 'create', 'truncate'];
            if (notes.length < 10) {
                error.innerHTML = 'Notes must be at least 10 characters';
                return;
            }
            for (var i = 0; i < sqlKeywords.length; i++) {
                if (notes.toUpperCase().includes(sqlKeywords[i])) {
                    error.innerHTML = 'Notes cannot contain SQL queries';
                    return;
                }
            }
            error.innerHTML = '';
        
    }
    function removeFromCart(product_id) {
        window.location.href = 'user.php?action=remove&product_id=' + product_id;
    }
    document.getElementById('product_search').addEventListener('keyup', function() {
                                            var input, filter, cards, cardContainer, h5, title, i;
                                            input = document.getElementById("product_search");
                                            filter = input.value.toUpperCase();
                                            cardContainer = document.getElementsByClassName("row-product");
                                            cards = cardContainer[0].getElementsByClassName(" product-col");
                                            for (i = 0; i < cards.length; i++) {
                                                title = cards[i].querySelector(".card-content-details h6");
                                                if (title.innerText.toUpperCase().indexOf(filter) > -1) {
                                                    cards[i].style.display = "";
                                                } else {
                                                    cards[i].style.display = "none";
                                                }
                                            }
                                        });

                                        document.getElementById('floatingTextarea2').addEventListener('input', function() {
                                localStorage.setItem('notes', this.value);
                            });

                            window.onload = function() {
                                let savedNotes = localStorage.getItem('notes');
                                let savedRoomSelection = localStorage.getItem('room_selection');
                                let cartIsEmpty = <?php echo empty($_SESSION['cart']) ? 'true' : 'false'; ?>;

                                if (cartIsEmpty) {
                                    localStorage.removeItem('notes');
                                    localStorage.removeItem('room_selection');
                                    document.getElementById('floatingTextarea2').value = ''; 
                                    document.querySelector('select[name="room_selection"]').value = '0';
                                } else {
                                    if (savedNotes) {
                                        document.getElementById('floatingTextarea2').value = savedNotes;
                                    }

                                    if (savedRoomSelection) {
                                        document.querySelector('select[name="room_selection"]').value = savedRoomSelection;
                                    }
                                }
                                
                                document.querySelector('select[name="room_selection"]').addEventListener('change', function() {
                                    localStorage.setItem('room_selection', this.value);
                                });
                            };

                            document.getElementById('orderForm').addEventListener('submit', function() {
                                localStorage.removeItem('notes');
                                localStorage.removeItem('room_selection');
                            });

                            document.getElementById('floatingTextarea2').addEventListener('input', function() {
                                localStorage.setItem('notes', this.value);
                            });
</script>
</body>
</html>
