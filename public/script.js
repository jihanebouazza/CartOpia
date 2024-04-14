document.addEventListener("DOMContentLoaded", function () {
  const dropdownBtn = document.querySelector(".user-btn");
  const dropdownMenu = document.querySelector(".dropdown-menu");

  dropdownBtn.addEventListener("click", function (event) {
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
  document.addEventListener("click", function (event) {
    if (
      !dropdownBtn.contains(event.target) &&
      !dropdownMenu.contains(event.target)
    ) {
      dropdownMenu.style.opacity = "0";
      dropdownMenu.style.transform = "translateY(-10px)";
      dropdownMenu.style.pointerEvents = "none";
    }
  });
});

if (!navigator.geolocation) {
  throw new Error("No geolocation available");
}

let lat;
let lng;

function success(pos) {
  lat = pos.coords.latitude;
  lng = pos.coords.longitude;
  // console.log(lat, lng);
  const accuracy = pos.coords.accuracy; // Accuracy in metres
  getCityAndCountry(lat, lng); // Call getCityAndCountry function after getting coordinates
}

function error(err) {
  if (err.code === 1) {
    // alert("Veuillez autoriser l'accès à la géolocalisation.");
    document.getElementById("geolocation").innerHTML +=
      "Veuillez autoriser l'accès<br> à la géolocalisation.";
  } else {
    // alert("Impossible d'obtenir la localisation actuelle.");
    document.getElementById("geolocation").innerHTML +=
      "Impossible d'obtenir<br> la localisation actuelle";
  }
}

const options = {
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 2000,
};

navigator.geolocation.getCurrentPosition(success, error, options);

async function getCityAndCountry(lat, long) {
  const url = `https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${long}&format=json`;

  try {
    const response = await fetch(url);
    const data = await response.json();

    const city =
      data.address.city ||
      data.address.town ||
      data.address.village ||
      data.address.hamlet ||
      "";
    const country = data.address.country || "";

    // console.log(`City: ${city}, Country: ${country}`);
    document.getElementById("geolocation").innerHTML += `${city}, ${country}`;
  } catch (error) {
    console.error("Error fetching location data:", error);
  }
}

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
    // Do something with the password, such as checking its length or complexity
    console.log("Password:", password);
  });
});
