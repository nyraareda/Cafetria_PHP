const productNameError = document.getElementById('productNameError');
const priceError = document.getElementById('priceError');
const productImageError = document.getElementById('productImageError');
const addCategoryModal = $('#addCategoryModal');
const confirmModal = $('#confirmModal');

function showError(element, message) {
    element.innerHTML = message;
    element.style.display = 'block';
}

function hideError(element) {
    element.style.display = 'none';
}

function validateProductName(productName) {
    return /^[A-Za-z\s]+$/.test(productName);
}

function validatePrice(price) {
    return price.trim() !== '' && parseFloat(price) !== 0; // Check if price is not empty and not equal to 0
}

function validateProductImage(productImage) {
    if (!productImage) return false; // No file selected
    const allowedExtensions = ['jpg', 'jpeg', 'png'];
    const extension = productImage.name.split('.').pop().toLowerCase();
    const maxSizeMB = 5; // Maximum file size in megabytes
    const maxSizeBytes = maxSizeMB * 1024 * 1024; // Convert megabytes to bytes
    return allowedExtensions.includes(extension) && productImage.size <= maxSizeBytes;
}

// Function to handle existence check AJAX request
function handleExistenceCheck(xhr, successCallback) {
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            successCallback(xhr.responseText);
        }
    };
}

// Function to handle category addition AJAX request
function addCategory(categoryName) {
    const xhrAddCategory = new XMLHttpRequest();
    xhrAddCategory.open('POST', 'addFunction.php', true);
    xhrAddCategory.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhrAddCategory.onreadystatechange = function() {
        if (xhrAddCategory.readyState === XMLHttpRequest.DONE && xhrAddCategory.status === 200) {
            const select = document.getElementById('category');
            const option = document.createElement('option');
            option.text = categoryName;
            select.add(option);
            addCategoryModal.modal('hide');
            Swal.fire("Success", "Category added successfully!", "success");
        }
    };
    xhrAddCategory.send('categoryName=' + categoryName);
}

// Form validation before submission
document.getElementById('addProductForm').addEventListener('submit', function(event) {
    const productName = document.getElementById('productName').value.trim();
    const price = document.getElementById('price').value.trim();
    const productImage = document.getElementById('productImage').files[0];

    let errors = [];

    if (productName.length < 3 || !validateProductName(productName)) {
        errors.push('Please enter a valid Product Name (at least 3 characters and letters only).');
        showError(productNameError, errors[errors.length - 1]);
    } else {
        hideError(productNameError);
    }

    if (!validatePrice(price)) {
        errors.push('Please enter a valid price.');
        showError(priceError, errors[errors.length - 1]);
    } else {
        hideError(priceError);
    }

    if (!validateProductImage(productImage)) {
        errors.push('Please upload a valid image file (jpg, jpeg, png, gif) up to 5MB in size.');
        showError(productImageError, errors[errors.length - 1]);
    } else {
        hideError(productImageError);
    }

    if (errors.length > 0) {
        event.preventDefault();
    } else {
        // AJAX request to check for product existence
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'check_existence.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        handleExistenceCheck(xhr, function(responseText) {
            if (responseText === 'exists') {
                Swal.fire("Error", "Product already exists!", "error");
            } else {
                confirmModal.modal('show');
            }
        });
        xhr.send('productName=' + productName);
        event.preventDefault();
    }
});


// Event listener for confirmation button
document.getElementById('confirmButton').addEventListener('click', function() {
    // Submit the form after user confirmation
    document.getElementById('addProductForm').submit();
});

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

    // Adding a new category
    document.getElementById('addCategorySubmit').addEventListener('click', function() {
        const categoryName = document.getElementById('categoryName').value.trim();

        if (categoryName.length < 3 || !validateProductName(categoryName)) {
            showError(document.getElementById('categoryNameError'), 'Please enter a valid Category Name (at least 3 characters and letters only).');
            return;
        } else {
            hideError(document.getElementById('categoryNameError'));
        }

        // AJAX request to check for category existence
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'check_existence.php', true); // Change the file name as per the PHP file handling the request
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        handleExistenceCheck(xhr, function(responseText) {
            if (responseText === 'exists') {
                // If the category already exists, hide the modal and alert the user
                addCategoryModal.modal('hide');
                Swal.fire(" Info", "Category already exists!", "info");
            } else {
                // If the category doesn't exist, add it
                addCategory(categoryName);
            }
        });
        xhr.send('categoryName=' + categoryName);
    });
});
