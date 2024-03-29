<?php
include 'dbconnection.php';

$db = new db();

$conn = $db->get_connection();

if ($conn->connect_error) {
    die ("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    $result = $db->get_data("products WHERE id = $id");

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_name = $row["product_name"];
        $product_price = $row["product_price"];
        $is_active = $row["is_active"];

    }
}
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/styleForm.css">
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

        /* body {
            background-color: #111;
            font-family: "Signika Negative", sans-serif, Arial;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            color: white;
        }

        #mainwrapper {
            width: 100%;
        }

        
        .input {
            color:white;
            background-color: transparent;
            width: 35rem;
        }

        .input:focus {
            color: white;
            background-color: transparent;
        }

        .error-message {
            display: none;
            color: #dc3545;
        }

        footer {
            width: 100%;
            background-color: #6fb936;
        }

        .updatebtn {
            background-color: #6fb936;
        }
        option{
            background-color: black;
        }
        .forform {
            max-width: 65rem;
            padding: 3rem 0;
            margin:2rem 30rem;
            border: 1px solid #6fb936;
            border-radius: 10px;
            background-color: rgba(0, 0, 0, 0.5); Semi-transparent background
        } */
        /* Custom styles */
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

<body>

    <div id="mainwrapper">
        <div id="wrapper">
            <div class="forform">
                <h2>Edit Product</h2>
                <form action="update_product.php" method="post" enctype="multipart/form-data"
                    onsubmit="return validateForm()">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name:</label>
                        <input type="text" class="form-control input" id="product_name" name="product_name"
                            value="<?php echo $product_name; ?>">
                        <small id="name_error" class="error-message" style="display: none;">Invalid name (at least3
                            characters)</small>
                    </div>
                    <div class="mb-3">
                        <label for="product_price" class="form-label">product price:</label>
                        <input type="number" class="form-control input" id="product_price" name="product_price"
                            value="<?php echo $product_price; ?>">
                        <small id="price_error" class="error-message" style="display: none;">You should enter valid
                            price and not be empty</small>
                    </div>
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Active:</label>
                        <input type="checkbox" id="is_active" name="is_active" value="0" <?php if ($is_active == 1)
                            echo "checked"; ?>>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category:</label>
                        <select id="category" name="category" class="form-select input">
                            <?php
                            $categories = $db->get_categories();
                            while ($category = $categories->fetch_assoc()) {
                                echo "<option value=\"{$category['id']}\" " . ($category['id'] == $category_id ? "selected" : "") . ">{$category['category_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="product_image" class="form-label">Image:</label>
                        <input type="file" class="form-control input" id="product_image" name="product_image">
                        <small id="image_error" class="error-message" style="display: none;">Please select an
                            image.</small>
                    </div>
                    <button type="submit" class="btn btn-primary updatebtn">Update Product</button>
                    <a href="allProducts.php" class="btn btn-secondary">Cancel</a>
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

        function validateForm() {
            var name = document.getElementById("product_name").value;
            var price = document.getElementById("product_price").value;
            var image = document.getElementById("product_image").value;
            var isValid = true;

            // Validate name
            if (name.length < 3) {
                document.getElementById("name_error").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("name_error").style.display = "none";
            }


            // Validate room number
            if (price.length < 1) {
                document.getElementById("price_error").style.display = "block";
                isValid = false;
            } else {
                document.getElementById("price_error").style.display = "none";
            }


            // Validate image
            if (image !== "") {
                // Check if a file is selected
                var fileInput = document.getElementById('product_image');
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

    </script>
</body>

</html>