const plus = document.querySelector(".plus");
const minus = document.querySelector(".minus");
const qtyInput = document.querySelector(".qty-input"); // Reference to the input element, not the value
const priceDisplay = document.querySelector(".product-price");

plus.addEventListener("click", () => {
  let currentValue = parseInt(qtyInput.value); // Get the current value as an integer
  qtyInput.value = currentValue + 1; // Increment and update the input's value
});

minus.addEventListener("click", () => {
  let currentValue = parseInt(qtyInput.value); // Get the current value as an integer
  if (currentValue > 1) {
    qtyInput.value = currentValue - 1; // Decrement and update the input's value
  }
});


