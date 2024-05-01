<?php
require '../../inc/navbar.php';

if (!is_logged_in()) {
  redirect('views/auth/login');
}

// Traitée (Processed): La commande a été confirmée et est en cours de préparation.
// Expédiée (Shipped): Les produits ont été envoyés au client.
// Livré (Delivered): La commande est arrivée à destination.
// Annulée (Cancelled): La commande a été annulée par l'utilisateur ou par le système en raison d'un problème.
