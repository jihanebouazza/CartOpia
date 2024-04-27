document.addEventListener("DOMContentLoaded", function () {
  const clearButton = document.querySelector(".clear");
  if (clearButton) {
    clearButton.addEventListener("click", function () {
      const form = document.querySelector(".products-main"); // Ensure you select your form correctly
      if (form) {
        // Reset all input fields
        // form.querySelector('[name="search"]').value = "";
        form
          .querySelectorAll('[type="checkbox"]')
          .forEach((checkbox) => (checkbox.checked = false));
        form
          .querySelectorAll('[type="number"], [type="range"]')
          .forEach((input) => {
            if (input.name === "minPrice") {
              input.value = 0; // Set this to your default min value
            } else if (input.name === "maxPrice") {
              input.value = 10000; // Set this to your default max value
            }
          });
        form.submit(); // Submit the form to refresh with all filters cleared
      }
    });
  }
});

// resubmitting  after clearing the search input

document.addEventListener("DOMContentLoaded", function() {
  const searchInput = document.querySelector('input[name="search"]');
  const form = document.querySelector('.products-main');

  searchInput.addEventListener('input', function() {
      // Check if the input is empty and the form is not already submitting
      if (this.value === '' && !this._submitting) {
          this._submitting = true;  // Prevent multiple submissions
          form.submit();
      }
  });

  // Optional: reset the submitting flag on form submission
  form.addEventListener('submit', function() {
      searchInput._submitting = false;
  });
});