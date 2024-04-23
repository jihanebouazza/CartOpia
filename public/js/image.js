function updateMainImage(selectedImageElement, src) {
  // Update the main image src
  document.getElementById('main-product-image').src = src;

  // Remove 'selected-image' class from all small images
  document.querySelectorAll('.small-img').forEach(img => {
      img.classList.remove('selected-image');
  });

  // Add 'selected-image' class to the clicked image
  selectedImageElement.classList.add('selected-image');
}