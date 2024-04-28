document.querySelectorAll(".plus, .minus").forEach((button) => {
  button.addEventListener("click", function () {
    const form = button.closest("form");
    const qtyInput = form.querySelector(".qty-input");
    let currentQty = parseInt(qtyInput.value);

    if (button.classList.contains("plus")) {
      qtyInput.value = currentQty + 1;
    } else if (button.classList.contains("minus")) {
      if (currentQty > 1) {
        qtyInput.value = currentQty - 1;
      }
    }

    form.submit(); // Submit the form automatically when buttons are clicked
  });
});
