



document.addEventListener("DOMContentLoaded", function () {
  const passwordInput = document.querySelector(".password_input");
  const showPasswordIcon = document.querySelector(".show-password");
  const hidePasswordIcon = document.querySelector(".hide-password");

  hidePasswordIcon.addEventListener("click", function () {
    passwordInput.type = "text";
    hidePasswordIcon.style.display = "none";
    showPasswordIcon.style.display = "block";
  });

  showPasswordIcon.addEventListener("click", function () {
    passwordInput.type = "password";
    hidePasswordIcon.style.display = "block";
    showPasswordIcon.style.display = "none";
  });

  passwordInput.addEventListener("input", function () {
    const password = passwordInput.value;
    console.log("Password:", password);
  });
});

