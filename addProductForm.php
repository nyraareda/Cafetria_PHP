<!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Add Product</title>
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

      <link rel="stylesheet" href="css/styleProduct.css">
      <!-- SweetAlert CSS -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
  </head>
    

<body>
  <div class="container ">  
  <nav class="navbar main-nav navbar-expand-lg navbar-dark fixed-top">
            <div class="container">
                <?php
                    session_start();
                    include 'dbconnection.php';

                    $db = new db();

                    $conn = $db->get_connection();

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $result = $db->get_data('Users WHERE role = "admin"');

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo '<a class="navbar-brand nav-brand nav-name" href="#">' . $row["user_name"] . '</a>';
                        echo '<img src="' . $row["user_image"] . '" alt="img" width="60" height="60" />'
                        ;
                    } else {
                        echo "0 results";
                    }
                ?>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ul-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item active">
                            <a class="nav-link nav-item-link" href="user.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-item-link" href="allProducts.php">Product</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-item-link" href="AllUsers.php">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-item-link" href="orders.php">Manual orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link nav-item-link" href="indexx.php">Checks</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
      <div class="row justify-content-center mt-5">
          <div class="col-md-6">
              <div class="containerProudct">
                  <form class="needs-validation" id="addProductForm" action="addFunction.php" method="post" enctype="multipart/form-data">
                      <div class="form-group py-2 ">
                          <label style="color:white" for="productName">Product Name:</label>
                          <input type="text" class="form-control prod-cont" id="productName" name="productName" >
                          <div id="productNameError" class="invalid-feedback">Please enter a valid Product Name (letters only).</div>
                      </div>
                      <div class="form-group py-2">
                          <label style="color:white" for="price">Price:</label>
                          <div class="input-group">
                          <input type="number" step="any" class="form-control prod-cont" min="0" id="price" name="price">
                              <div class="input-group-append ">
                                  <span class="input-group-text py-2">EGP</span>
                              </div>
                          </div>
                          <div id="priceError" class="invalid-feedback">Please enter a valid price.</div>
                      </div>
                      <div class="form-group py-2">
                          <label style="color:white" for="category">Category:</label>
                          <select class="form-control prod-cont" id="category" name="category">
                              <?php
                              session_start();
                              require_once 'dbconnection.php'; 
                              
                              $db = new db();
                              $categories = $db->get_data("Categories");
                              foreach ($categories as $category) {
                                  echo "<option value='" . $category['category_name'] . "'>" . $category['category_name'] . "</option>";
                              }
                              ?>
                          </select>
                          <small id="categoryHelp" class="form-text text-muted">
<a href="#" id="addCategoryLink" data-bs-toggle="modal" data-bs-target="#addCategoryModal">Add Category</a>
                          </small>
                      </div>
                      <div class="form-group py-2">
                          <label style="color:white" for="productImage">Product Image:</label>
                          <input type="file" class="form-control-file prod-cont" id="productImage" name="productImage" accept="image/*" >
                          <div id="productImageError" class="invalid-feedback">Please upload a product image.</div>
                      </div>
                      <button type="submit" class="btn btn-primary mainbtn green-btn" id="saveButton">Save</button>
                      <button type="reset" class="btn btn-secondary mainbtn green-btn">Reset</button>
                  </form>
              </div>
          </div>
      </div>
</div>

  <!-- Modal for confirm adding product -->
  <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Product Addition</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5>Are you sure you want to add this product?</h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelButton">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmButton">Yes</button>
            </div>
        </div>
    </div>
</div>
  
    <!-- Modal for adding category -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="form-group">
                        <label for="categoryName">Category Name:</label>
                        <input type="text" class="form-control" id="categoryName" name="categoryName" required>
                        <div id="categoryNameError" class="invalid-feedback">Please enter a valid Category Name (letters only).</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeCategoryModalButton" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="addCategorySubmit">Add Category</button>
            </div>
        </div>
    </div>
</div>



  <!-- 
  SweetAlert JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.js"></script>


  <!-- jQuery, Popper.js, Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<
<script src="js/AddProduct.js"></script>

<script>
 document.getElementById('cancelButton').addEventListener('click', function() {
    $('#confirmModal').modal('hide'); // Close the modal
});

$(document).ready(function() {
    // Add event listener to the Close button in the category modal
    $('#addCategoryModal').on('shown.bs.modal', function() {
        $('#closeCategoryModalButton').click(function() {
            $('#addCategoryModal').modal('hide'); // Close the modal
        });
    });
});

</script>

</body>
  </html>
