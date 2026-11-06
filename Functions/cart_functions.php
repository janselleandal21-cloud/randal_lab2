
<?php

require_once __DIR__ . '/movie_functions.php';


// ADD MOVIE TO CART
function addToCart($movieId, $quantity) {

    // Check if the movie exists.
    if (getMovieById($movieId) === false) {
        return false;
    }

    // Validate quantity.
    if (!is_int($quantity) || $quantity < 1) {
        return false;
    }

    // Create cart if it does not exist.
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Get the existing quantity.
    $currentQuantity = isset($_SESSION['cart'][$movieId])
        ? $_SESSION['cart'][$movieId]
        : 0;

    // Maximum of 10 tickets per movie.
    if (($currentQuantity + $quantity) > 10) {
        return false;
    }

    // Add the new quantity.
    $_SESSION['cart'][$movieId] = $currentQuantity + $quantity;

    return true;

}


// UPDATE CART QUANTITY
function updateCart($movieId, $quantity) {

    if (!isset($_SESSION['cart'][$movieId])) {
        return false;
    }

    if (!is_int($quantity) || $quantity < 1 || $quantity > 10) {
        return false;
    }

    $_SESSION['cart'][$movieId] = $quantity;

    return true;

}


// REMOVE MOVIE FROM CART
function removeFromCart($movieId) {

    if (!isset($_SESSION['cart'][$movieId])) {
        return false;
    }

    unset($_SESSION['cart'][$movieId]);

    return true;

}


// GET COMPLETE CART INFORMATION
function getCartItems() {

    $cartItems = [];

    // Check if the cart is empty.
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        return $cartItems;
    }

    // Process every movie in the session cart.
    foreach ($_SESSION['cart'] as $movieId => $quantity) {

        // Validate movie ID and quantity.
        $movieId = filter_var($movieId, FILTER_VALIDATE_INT);
        $quantity = filter_var($quantity, FILTER_VALIDATE_INT);

        if ($movieId === false || $movieId < 1) {
            continue;
        }

        if ($quantity === false || $quantity < 1 || $quantity > 10) {
            continue;
        }

        // Retrieve movie information.
        $movie = getMovieById($movieId);

        // Skip invalid movie records.
        if ($movie === false) {
            continue;
        }

        // Add quantity to movie information.
        $movie['quantity'] = $quantity;

        // Calculate the total price for this movie.
        $movie['item_total'] = $movie['price'] * $quantity;

        // Store the completed movie information.
        $cartItems[] = $movie;

    }

    return $cartItems;

}


// CALCULATE CART SUBTOTAL
function calculateSubtotal() {

    $cartItems = getCartItems();

    $subtotal = 0;

    // Add the item total of every movie.
    foreach ($cartItems as $item) {

        $subtotal += $item['item_total'];

    }

    return $subtotal;

}

?>
