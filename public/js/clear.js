document.addEventListener("DOMContentLoaded", function () {
  const clearButton = document.querySelector(".clear");
  if (clearButton) {
    clearButton.addEventListener("click", function () {
      const form = document.querySelector(".filter-container"); // Ensure you select your form correctly
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
  const searchInput = document.querySelector('.searching');
  const form = document.querySelector('.search-container');

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

// document.addEventListener("DOMContentLoaded", function() {
//   const searchInput = document.querySelector('input[name="search"]');
//   const form = document.querySelector('.search-container');

//   if (!searchInput || !form) {
//       console.error('Search input or form not found.');
//       return; // Stop the script if elements are not found
//   }

//   searchInput.addEventListener('input', function() {
//       console.log('Input event triggered. Current value:', this.value);
//       // Check if the input is empty and the form is not already submitting
//       if (this.value.trim() === '' && !this._submitting) {
//           this._submitting = true;  // Prevent multiple submissions
//           console.log('Form submission triggered due to empty input.');
//           form.submit();
//       }
//   });

//   form.addEventListener('submit', function() {
//       console.log('Form is submitting...');
//       searchInput._submitting = false; // Reset the flag upon form submission
//   });
// });
