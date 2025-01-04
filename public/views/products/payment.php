<?php
require '../../inc/header.php';
require_once dirname(__DIR__) . '/../../' . 'vendor/autoload.php';
\Stripe\Stripe::setApiKey(STRIPE_API_KEY);

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
  $line_items = [];
  $subtotal = 0;

  foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product_details = getProductByID($product_id);
    if ($product_details) {
      // Use the getProductPrice function to fetch the product price in MAD
      $product_price_in_mad = getProductPrice($product_id); // Assuming this function returns the price in MAD
      $product_price_in_centimes = intval($product_price_in_mad * 100); // Convert price to centimes for Stripe
      $subtotal += $product_price_in_centimes * $quantity;

      $line_items[] = [
        "quantity" => $quantity,
        "price_data" => [
          "currency" => "mad",
          "unit_amount" => $product_price_in_centimes,
          "product_data" => [
            "name" => $product_details['title']
          ],
        ],
      ];
    }
  }

  // Calculate delivery fee as 10% of the subtotal (ensure integer conversion for Stripe)
  $delivery_fee = intval(0.10 * $subtotal);

  // Add delivery fee as a separate line item
  $line_items[] = [
    "quantity" => 1,
    "price_data" => [
      "currency" => "mad",
      "unit_amount" => $delivery_fee,
      "product_data" => [
        "name" => "Shipping fees"
      ],
    ],
  ];

  if (!empty($line_items)) {
    $checkout_session = \Stripe\Checkout\Session::create([
      "payment_method_types" => ["card"],
      "mode" => "payment",
      "success_url" => STRIPE_SUCCESS_URL,
      "cancel_url" => STRIPE_CANCEL_URL,
      "locale" => "fr",
      "line_items" => $line_items
    ]);
    // if statement 
    http_response_code(303);
    header("Location: " . $checkout_session->url);
    exit;
  } else {
    set_message('The cart is empty or the products could not be loaded.', 'error');
  }
} else {
  set_message('The cart is empty.', 'error');
}
