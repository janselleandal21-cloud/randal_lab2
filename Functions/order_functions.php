
<?php

require_once __DIR__ . '/cart_functions.php';
require_once __DIR__ . '/discount_functions.php';
require_once __DIR__ . '/validation_functions.php';


// PROCESS AND SAVE CUSTOMER ORDER
function processOrder($customer, $promoCode = "") {

    // CHECK CUSTOMER INFORMATION
    $nameError = validateCustomerName($customer['name']);

    $emailError = validateCustomerEmail($customer['email']);

    if ($nameError != "" || $emailError != "") {
        return [
            "success" => false,
            "message" => "Invalid customer information."
        ];
    }


    // VALIDATE CART
    $cartErrors = validateCartContents();

    if (!empty($cartErrors)) {
        return [
            "success" => false,
            "message" => "Your cart contains invalid items."
        ];
    }


    // GET CART ITEMS
    $cartItems = getCartItems();

    if (empty($cartItems)) {
        return [
            "success" => false,
            "message" => "Your shopping cart is empty."
        ];
    }


    // CALCULATE SUBTOTAL
    $subtotal = calculateSubtotal();


    // CALCULATE HIGHEST ELIGIBLE DISCOUNT
    $discount = calculateDiscount($subtotal, $promoCode);


    // CALCULATE FINAL PAYABLE AMOUNT
    $total = calculateTotal(
        $subtotal,
        $discount['discount_amount']
    );


    // GENERATE ORDER IDENTIFICATION
    $orderId = "ORD-" . date("Ymd") . "-" . strtoupper(bin2hex(random_bytes(3)));


    // PREPARE FINALIZED ORDER INFORMATION
    $order = [

        "order_id" => $orderId,

        "order_date" => date("Y-m-d H:i:s"),

        "customer" => [
            "name" => trim($customer['name']),
            "email" => trim($customer['email'])
        ],

        "items" => $cartItems,

        "subtotal" => $subtotal,

        "discount" => $discount['discount_amount'],

        "discount_name" => $discount['discount_name'],

        "promo_code" => $discount['promo_code'],

        "total" => $total,

        "order_status" => "Confirmed",

        "payment_status" => "Simulated - No real payment processed"

    ];


    // SAVE FINALIZED ORDER IN SESSION
    $_SESSION['last_order'] = $order;


    // CLEAR SHOPPING CART AFTER SUCCESSFUL ORDER
    $_SESSION['cart'] = [];


    // CLEAR TEMPORARY CHECKOUT INFORMATION
    unset($_SESSION['checkout_customer']);

    unset($_SESSION['checkout_promo']);

    
    return [
        "success" => true,
        "message" => "Order successfully processed.",
        "order_id" => $orderId
    ];

}

?>
