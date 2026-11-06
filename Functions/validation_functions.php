
<?php

require_once __DIR__ . '/movie_functions.php';


// VALIDATE CUSTOMER NAME
function validateCustomerName($name) {

    $name = trim($name);

    if ($name == "") {
        return "Customer name is required.";
    }

    if (strlen($name) < 2) {
        return "Customer name must contain at least 2 characters.";
    }

    if (strlen($name) > 100) {
        return "Customer name must not exceed 100 characters.";
    }

    if (!preg_match("/^[\p{L}\p{M} .'-]+$/u", $name)) {
        return "Customer name contains invalid characters.";
    }

    return "";

}


// VALIDATE CUSTOMER EMAIL
function validateCustomerEmail($email) {

    $email = trim($email);

    if ($email == "") {
        return "Email address is required.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Please enter a valid email address.";
    }

    return "";

}


// VALIDATE CART CONTENTS
function validateCartContents() {

    $errors = [];

    if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart']) || empty($_SESSION['cart'])) {

        $errors[] = "Your shopping cart is empty.";

        return $errors;

    }


    foreach ($_SESSION['cart'] as $movieId => $quantity) {

        $validMovieId = filter_var($movieId, FILTER_VALIDATE_INT);

        $validQuantity = filter_var($quantity, FILTER_VALIDATE_INT);


        if ($validMovieId === false || $validMovieId < 1) {

            $errors[] = "Invalid movie ID found in your cart.";

            continue;

        }


        $movie = getMovieById($validMovieId);

        if ($movie === false) {

            $errors[] = "A movie in your cart does not exist.";

            continue;

        }


        if ($validQuantity === false || $validQuantity < 1 || $validQuantity > 10) {

            $errors[] = "Invalid ticket quantity for " . $movie['title'] . ".";

        }

    }


    return $errors;

}

?>
