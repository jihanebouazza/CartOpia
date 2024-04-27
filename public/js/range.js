// const rangeInput = document.querySelectorAll(".range-input input"),
//   priceInput = document.querySelectorAll(".price-input input"),
//   progress = document.querySelector(".slider .progress");

// let priceGap = 1000;

// priceInput.forEach((input) => {
//   input.addEventListener("input", (e) => {
//     let minVal = parseInt(priceInput[0].value),
//       maxVal = parseInt(priceInput[1].value);

//     if (maxVal - minVal >= priceGap && maxVal <= 10000) {
//       if (e.target.className === "input-min") {
//         // if active input is min slider
//         rangeInput[0].value = minVal;
//         progress.style.left = (minVal / rangeInput[0].max) * 100 + "%";
//       } else {
//         rangeInput[1].value = maxVal;
//         progress.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
//       }
//     }
//   });
// });

// rangeInput.forEach((input) => {
//   input.addEventListener("input", (e) => {
//     let minVal = parseInt(rangeInput[0].value),
//       maxVal = parseInt(rangeInput[1].value);

//     if (maxVal - minVal < priceGap) {
//       if (e.target.className === "range-min") {
//         // if active slider is min slider
//         rangeInput[0].value = maxVal - priceGap;
//       } else {
//         rangeInput[1].value = minVal + priceGap;
//       }
//     } else {
//       priceInput[0].value = minVal;
//       priceInput[1].value = maxVal;
//       progress.style.left = (minVal / rangeInput[0].max) * 100 + "%";
//       progress.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
//     }
//   });
// });

// ---------------------------------------------------------
// const rangeInput = document.querySelectorAll(".range-input input"),
//   priceInput = document.querySelectorAll(".price-input input"),
//   progress = document.querySelector(".slider .progress");

// let priceGap = 1000;

// priceInput.forEach((input) => {
//   input.addEventListener("input", (e) => {
//     let minVal = parseInt(priceInput[0].value),
//       maxVal = parseInt(priceInput[1].value);

//     if (maxVal - minVal < priceGap) {
//       if (e.target.className === "input-min") {
//         minVal = maxVal - priceGap;
//       } else {
//         maxVal = minVal + priceGap;
//       }
//     }

//     if (minVal < 0) minVal = 0;
//     if (maxVal > 10000) maxVal = 10000;
    
//     priceInput[0].value = minVal;
//     priceInput[1].value = maxVal;
//     rangeInput[0].value = minVal;
//     rangeInput[1].value = maxVal;

//     progress.style.left = (minVal / rangeInput[0].max) * 100 + "%";
//     progress.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
//   });
// });

// rangeInput.forEach((input) => {
//   input.addEventListener("input", (e) => {
//     let minVal = parseInt(rangeInput[0].value),
//       maxVal = parseInt(rangeInput[1].value);

//     if (maxVal - minVal < priceGap) {
//       if (e.target.className === "range-min") {
//         minVal = maxVal - priceGap;
//       } else {
//         maxVal = minVal + priceGap;
//       }
//     }

//     priceInput[0].value = minVal;
//     priceInput[1].value = maxVal;

//     progress.style.left = (minVal / rangeInput[0].max) * 100 + "%";
//     progress.style.right = 100 - (maxVal / rangeInput[1].max) * 100 + "%";
//   });
// });

document.addEventListener("DOMContentLoaded", function() {
  updateSliderPositionAndColor();
});

function updateSliderPositionAndColor() {
  const rangeInputs = document.querySelectorAll(".range-input input"),
        priceInputs = document.querySelectorAll(".price-input input"),
        progress = document.querySelector(".slider .progress");

  let minVal = parseInt(rangeInputs[0].value, 10),
      maxVal = parseInt(rangeInputs[1].value, 10);

  progress.style.left = (minVal / rangeInputs[0].max) * 100 + "%";
  progress.style.right = 100 - (maxVal / rangeInputs[1].max) * 100 + "%";
}

const rangeInput = document.querySelectorAll(".range-input input"),
    priceInput = document.querySelectorAll(".price-input input");

let priceGap = 1000;

rangeInput.forEach((input) => {
  input.addEventListener("input", (e) => {
      let minVal = parseInt(rangeInput[0].value, 10),
          maxVal = parseInt(rangeInput[1].value, 10);

      if (maxVal - minVal < priceGap) {
          if (e.target.className === "range-min") {
              minVal = maxVal - priceGap;
          } else {
              maxVal = minVal + priceGap;
          }
      }

      priceInput[0].value = minVal;
      priceInput[1].value = maxVal;

      updateSliderPositionAndColor();
  });
});

priceInput.forEach((input) => {
  input.addEventListener("input", (e) => {
      let minVal = parseInt(priceInput[0].value, 10),
          maxVal = parseInt(priceInput[1].value, 10);

      if (maxVal - minVal < priceGap) {
          if (e.target.className === "input-min") {
              minVal = maxVal - priceGap;
          } else {
              maxVal = minVal + priceGap;
          }
      }

      rangeInput[0].value = minVal;
      rangeInput[1].value = maxVal;

      updateSliderPositionAndColor();
  });
});
