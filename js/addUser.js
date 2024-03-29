
document.getElementById("registrationForm").addEventListener("submit", function (event) {
    // event.preventDefault(); // Commented out to allow normal form submission
    validateRegistrationForm();
});

document.getElementById("registrationForm").addEventListener("reset", function (event) {
    clearFormFields();
});

function validateRegistrationForm() {
    let nameInput = document.getElementById("nameField");
    let emailInput = document.getElementById("emailField");
    let passwordInput = document.getElementById("passwordField");
    let confirmPasswordInput = document.getElementById("confirmpasswordField");
    let roomNoInput = document.getElementById("roomnumberField");
    let extNumberInput = document.getElementById("extnumberField");
    let imageInput = document.getElementById("imageField");

    let name = nameInput.value;
    let email = emailInput.value;
    let password = passwordInput.value;
    let confirmPassword = confirmPasswordInput.value;
    let roomNo = roomNoInput.value;
    let extNumber = extNumberInput.value;
    let image = imageInput.value;

    let nameValid = validateName(name);
    let emailValid = validateEmail(email);
    let passwordValid = validatePassword(password);
    let confirmPasswordValid = validateConfirmPassword(password, confirmPassword);
    let roomNoValid = validateRoomNumber(roomNo);
    let extNumberValid = validateExtNumber(extNumber);
    let imageValid = validateImage(image);

    if (nameValid && emailValid && passwordValid && confirmPasswordValid && roomNoValid && extNumberValid) {
    } else {
        event.preventDefault();
    }
}

function validateName(name) {
    let nameRegex = /^[a-zA-Z][a-zA-Z]*$/;
    let nameError = document.querySelector("#nameField + .error-message");

    if (nameRegex.test(name)) {
        nameError.style.display = "none";
        return true;
    } else {
        nameError.style.display = "block";
        return false;
    }
}

function validateEmail(email) {
    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    let emailError = document.querySelector("#emailField + .error-message");

    if (emailRegex.test(email)) {
        emailError.style.display = "none";
        return true;
    } else {
        emailError.style.display = "block";
        return false;
    }
}

function validatePassword(password) {
    let passwordRegex = /^.{8,}$/;
    let passwordError = document.querySelector("#passwordField + .error-message");

    if (passwordRegex.test(password)) {
        passwordError.style.display = "none";
        return true;
    } else {
        passwordError.style.display = "block";
        return false;
    }
}

function validateConfirmPassword(password, confirmPassword) {
    let confirmPasswordError = document.querySelector("#confirmpasswordField + .error-message");

    if (password === confirmPassword) {
        confirmPasswordError.style.display = "none";
        return true;
    } else {
        confirmPasswordError.style.display = "block";
        return false;
    }
}

function validateRoomNumber(roomNo) {
    let roomNoRegex = /^\d{1,3}$/;  
    let roomNoError = document.querySelector("#roomnumberField + .error-message");

    if (roomNoRegex.test(roomNo)) {
        roomNoError.style.display = "none";
        return true;
    } else {
        roomNoError.style.display = "block";
        return false;
    }
}

function validateExtNumber(extNumber) {
    let extNumberRegex = /^\d{1,3}$/;  
    let extNumberError = document.querySelector("#extnumberField + .error-message");

    if (extNumberRegex.test(extNumber)) {
        extNumberError.style.display = "none";
        return true;
    } else {
        extNumberError.style.display = "block";
        return false;
    }
}

function validateImage(image) {
    let imageInput = document.getElementById("imageField");
    let imageError = document.querySelector("#imageField + .error-message");

    if (imageInput.files.length > 0) {
        let allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
        let fileSizeLimit = 5 * 1024 * 1024; // 5MB

        let file = imageInput.files[0];
        let fileName = file.name;
        let fileSize = file.size;

        if (!allowedExtensions.exec(fileName)) {
            imageError.style.display = "block";
            imageError.textContent = "Invalid file type. Allowed file types are .jpg, .jpeg, .png, and .gif.";
            return false;
        } else if (fileSize > fileSizeLimit) {
            imageError.style.display = "block";
            imageError.textContent = "File size exceeds the limit of 5MB.";
            return false;
        } else {
            imageError.style.display = "none";
            return true;
        }
    } else {
        imageError.style.display = "block";
        imageError.textContent = "Please select an image.";
        return false;
    }
}

function clearFormFields() {
    let nameInput = document.getElementById("nameField");
    let emailInput = document.getElementById("emailField");
    let passwordInput = document.getElementById("passwordField");
    let confirmPasswordInput = document.getElementById("confirmpasswordField");
    let roomNoInput = document.getElementById("roomnumberField");
    let extNumberInput = document.getElementById("extnumberField");
    let imageInput = document.getElementById("imageField");

    nameInput.value = "";
    emailInput.value = "";
    passwordInput.value = "";
    confirmPasswordInput.value = "";
    roomNoInput.value = "";
    extNumberInput.value = "";
    imageInput.value = "";

    let errorMessages = document.querySelectorAll(".error-message");
    errorMessages.forEach(function (errorMessage) {
        errorMessage.style.display = "none";
    });
}