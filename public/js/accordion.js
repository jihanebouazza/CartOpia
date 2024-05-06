document.addEventListener("DOMContentLoaded", function () {
  const accordions = document.querySelectorAll(".contentBox");

  accordions.forEach((accordion) => {
    const label = accordion.querySelector(".accordion-label");
    const content = accordion.querySelector(".accordion-content");

    label.addEventListener("click", () => {
      const isOpen = content.style.height;
      content.style.height = isOpen ? "" : `${content.scrollHeight + 24}px`;
      content.style.padding = isOpen ? "" : `16px 24px`;
      content.style.border = isOpen ? "" : `1px solid #BAB3B2`;
      content.style.borderTop = isOpen ? "" : `none`;
      content.style.borderBottomLeftRadius = isOpen ? "" : `8px`;
      content.style.borderBottomRightRadius = isOpen ? "" : `8px`;
    });
  });
});
