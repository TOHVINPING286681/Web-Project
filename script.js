const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');


registerBtn.addEventListener('click', () => {
    container.classList.add("active");
});

loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

function validateForm() {
    var name = document.getElementById("name").value;
    var username = document.getElementById("username").value;
    var phone = document.getElementById("phone").value;
    var email = document.getElementById("email").value;
    var passa = document.getElementById("passa").value;
    var passb = document.getElementById("passb").value;

    if (name === "" || username === "" || phone === "" || email === "" || passa === "" || passb === "") {
      document.getElementById("error-message").innerText = "Please fill in all fields.";
      return false;
    }

    if (passa !== passb) {
      document.getElementById("error-message").innerText = "Passwords do not match.";
      return false;
    }

    return true;
  }

function validateLoginForm() {
    var loginEmail = document.getElementById("loginEmail").value;
    var loginPassword = document.getElementById("loginPassword").value;

    if (loginEmail === "" || loginPassword === "") {
      document.getElementById("login-error-message").innerText = "Please fill in all fields.";
      return false;
    }

    // Additional login validation logic can be added here

    return true;
}