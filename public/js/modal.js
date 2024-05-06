const modalBtn = document.querySelector(".modal-btn");
const modalCloseBtn = document.querySelector(".modal-close");
const modalBg = document.querySelector(".modal-bg");

modalBtn.addEventListener("click", () => {
  modalBg.classList.toggle("bg-active");
});
modalCloseBtn.addEventListener("click", () => {
  modalBg.classList.toggle("bg-active");
});
