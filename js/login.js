document.querySelector("#forgotPasswordLink").addEventListener("click", function (event) {
    // event.preventDefault();
     alert("Forgot Password link clicked!");
 });

function validateForm() {
    let emailInput = document.getElementById("emailField");
    let passwordInput = document.getElementById("passwordField");

    let email = emailInput.value;
    let password = passwordInput.value;

    let emailValid = validateEmail(email);
    let passwordValid = validatePassword(password);

    let emailError = document.querySelector('.email-error');
    let passwordError = document.querySelector('.password-error');

    if (!emailValid) {
        emailError.style.display = 'block';
        emailInput.classList.add('error');
        return false;
    } else {
        emailError.style.display = 'none';
        emailInput.classList.remove('error');
    }

    if (!passwordValid) {
        passwordError.style.display = 'block';
        passwordInput.classList.add('error');
        return false;
    } else {
        passwordError.style.display = 'none';
        passwordInput.classList.remove('error');
    }

    return true;
}

function validateEmail(email) {
    let re = /(.+)@(.+){2,}\.(.+){2,}/;
    return re.test(email.toLowerCase());
}

function validatePassword(password) {
    return password.length >= 8;
}
