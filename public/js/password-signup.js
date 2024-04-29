// password validation
function validatePassword(password) {
  const passwordValidationDiv = document.querySelector('.password-validation');
  const passwordDivs = passwordValidationDiv.querySelectorAll('.password-init');

  const hasDigits = /\d/.test(password);
  const hasUppercase = /[A-Z]/.test(password);
  const hasSpecialChars = /[!@#$%^&*(),.?":{}|]/.test(password);
  const isMinLength = password.length >= 8;

  passwordDivs[0].classList.toggle('password-div-error', !hasDigits);
  passwordDivs[0].classList.toggle('password-div', hasDigits);
  passwordDivs[0].querySelector('i').classList.toggle('fa-check', hasDigits);
  passwordDivs[0].querySelector('i').classList.toggle('fa-x', !hasDigits);

  passwordDivs[1].classList.toggle('password-div-error', !hasUppercase);
  passwordDivs[1].classList.toggle('password-div', hasUppercase);
  passwordDivs[1].querySelector('i').classList.toggle('fa-check', hasUppercase);
  passwordDivs[1].querySelector('i').classList.toggle('fa-x', !hasUppercase);

  passwordDivs[2].classList.toggle('password-div-error', !hasSpecialChars);
  passwordDivs[2].classList.toggle('password-div', hasSpecialChars);
  passwordDivs[2].querySelector('i').classList.toggle('fa-check', hasSpecialChars);
  passwordDivs[2].querySelector('i').classList.toggle('fa-x', !hasSpecialChars);

  passwordDivs[3].classList.toggle('password-div-error', !isMinLength);
  passwordDivs[3].classList.toggle('password-div', isMinLength);
  passwordDivs[3].querySelector('i').classList.toggle('fa-check', isMinLength);
  passwordDivs[3].querySelector('i').classList.toggle('fa-x', !isMinLength);
}

document.querySelector('input[name="password"]').addEventListener('input', function(e) {
  validatePassword(e.target.value);
});

document.querySelector('input[name="password"]').addEventListener('focus', function() {
  document.querySelector('.password-validation').style.display = 'flex';
});

document.querySelector('input[name="password"]').addEventListener('blur', function() {
  document.querySelector('.password-validation').style.display = 'none';
});