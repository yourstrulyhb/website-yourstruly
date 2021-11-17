// This code is based on a Youtube tutorial: https://youtu.be/QCVYjqz0WK8

// Validate new password as user inputs it
// If it doesn't meet requirements, disable register button
let parameters = {
    len : false,
    letters : false,
    numbers : false,
    specialChars : false
}
let strengthBar = document.getElementById("password-check-bar");    // Password strength bar

// Check that password meets requirements
// Only in register page
function passwordStrengthChecker() {
    let password = document.getElementById("auth_pass").value;  // Password field

    // Check if password meets requirements
    // Change the value in object parameters
    parameters.letters = (/[A-Z]+/.test(password)) ? true : false;      // Check if password has big letters
    parameters.numbers = (/[0-9]+/.test(password)) ? true : false;      // Check if password has numbers
    parameters.specialChars = (/[!\"$%&/()=?@~`\\.\';:+=^*_-]+/.test(password)) ? true : false;     // Check if password has special characters
    parameters.len = (password.length > 7) ? true : false;      // Check if password length is greater than 7

    // Determine length and create divisions of strength bar
    let barLength = Object.values(parameters).filter(value=>value);

    // Insert child elements for password strength bar
    strengthBar.innerHTML = "";
    for (let i in barLength) {      // Creates span elements for the strength bar based on number of true parameters
        let span = document.createElement("span");
        span.classList.add("strength");
        strengthBar.appendChild(span);      // Insert span element to the strength bar
    }
    // Design the newly created span elements
    let spanRef = document.getElementsByClassName("strength");      // Get class name of span in strength bar
    let reg_btn = document.getElementById('register-btn');      // Register button

    // Change color of strength bar based on number of span elements in strength bar
    // And enable/disable register button 
    for (let i = 0; i < spanRef.length; i++) {
        switch (spanRef.length - 1) {       
            case 0 :        // If number of span elements = 1
                spanRef[i].style.background = "#ff3e36";
                reg_btn.disabled = true;
                break;
            case 1:     // If number of span elements = 2
                spanRef[i].style.background = "#ff691f";
                reg_btn.disabled = true;
                break;
            case 2:     // If number of span elements = 3
                spanRef[i].style.background = "#ffda36";
                reg_btn.disabled = true;
                break;
            case 3:     // If number of span elements = 4
                spanRef[i].style.background = "#0be881";
                reg_btn.disabled = false;       // Enable register button only if number of span elements fill the strength bar
                break;
        }
    }
}

// Let user show password as text or hide them
function passwordToggle() {
    let password = document.getElementById("auth_pass");    // Password field
    let eye = document.getElementById("password-toggle");   // Eye icon/password toggle in password field

    if (password.getAttribute("type") == "password") {   // If type of password field input is password 
        password.setAttribute("type", "text");    // Change type to text to show password as text
        eye.style.color = "var(--primary-red1)";  // Change color of eye icon
    }
    else {   // If type of password field input is text
        password.setAttribute("type", "password");    // Hide password
        eye.style.color = "var(--primary-bluegreen1)";
    }
}
