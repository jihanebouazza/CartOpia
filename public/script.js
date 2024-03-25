document.addEventListener("DOMContentLoaded", function() {
  const dropdownBtn = document.querySelector('.user-btn');
  const dropdownMenu = document.querySelector('.dropdown-menu');

  dropdownBtn.addEventListener('click', function(event) {
    event.stopPropagation(); // Prevent the click event from bubbling up and triggering the document click event listener
    if (dropdownMenu.style.opacity === "1") {
      // Close the dropdown menu
      dropdownMenu.style.opacity = "0";
      dropdownMenu.style.transform = "translateY(-10px)";
      dropdownMenu.style.pointerEvents = "none";
    } else {
      // Open the dropdown menu
      dropdownMenu.style.opacity = "1";
      dropdownMenu.style.transform = "translateY(0)";
      dropdownMenu.style.pointerEvents = "auto";
    }
  });

  // Close dropdown menu if clicked outside
  document.addEventListener('click', function(event) {
    if (!dropdownBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
      dropdownMenu.style.opacity = "0";
      dropdownMenu.style.transform = "translateY(-10px)";
      dropdownMenu.style.pointerEvents = "none";
    }
  });
});
