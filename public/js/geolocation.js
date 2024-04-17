// Geolocation
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