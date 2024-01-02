const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

registerBtn.addEventListener('click', () => {
  container.classList.add('active');
});

loginBtn.addEventListener('click', () => {
  container.classList.remove('active');
});

function validateLoginForm() {
  var loginEmail = document.getElementById('loginEmail').value;
  var loginPassword = document.getElementById('loginPassword').value;

  if (loginEmail === '' || loginPassword === '') {
    document.getElementById('login-error-message').innerText =
      'Please fill in all fields.';
    return false;
  }

  // Additional login validation logic can be added here

  return true;
}
