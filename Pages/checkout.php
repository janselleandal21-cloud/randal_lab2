
<?php

require_once __DIR__ . '/../Includes/init.php';

require_once __DIR__ . '/../Functions/cart_functions.php';
require_once __DIR__ . '/../Functions/discount_functions.php';
require_once __DIR__ . '/../Functions/validation_functions.php';
require_once __DIR__ . '/../Functions/order_functions.php';


$pageTitle = "Checkout | Cinema Ticket System";


// GET CART INFORMATION
$cartErrors = validateCartContents();

$cartItems = getCartItems();

$subtotal = calculateSubtotal();


// CUSTOMER INPUT VARIABLES
$customerName = "";
$customerEmail = "";
$promoCode = "";

$errors = [];

$orderMessage = "";


// PROCESS CHECKOUT FORM
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // GET CUSTOMER INPUT
    $customerName = isset($_POST['customer_name']) && is_string($_POST['customer_name'])
        ? trim($_POST['customer_name'])
        : "";

    $customerEmail = isset($_POST['customer_email']) && is_string($_POST['customer_email'])
        ? trim($_POST['customer_email'])
        : "";

    $promoCode = isset($_POST['promo_code']) && is_string($_POST['promo_code'])
        ? trim($_POST['promo_code'])
        : "";


    // VALIDATE CART AGAIN
    $cartErrors = validateCartContents();


    // VALIDATE CUSTOMER NAME
    $nameError = validateCustomerName($customerName);

    if ($nameError != "") {
        $errors[] = $nameError;
    }


    // VALIDATE CUSTOMER EMAIL
    $emailError = validateCustomerEmail($customerEmail);

    if ($emailError != "") {
        $errors[] = $emailError;
    }


    // PROCESS ORDER ONLY IF VALID
    if (empty($errors) && empty($cartErrors)) {

        $customer = [
            "name" => $customerName,
            "email" => $customerEmail
        ];


        // CALL ORDER PROCESSING FUNCTION
        $result = processOrder($customer, $promoCode);


        if ($result['success'] == true) {

            // REDIRECT TO RECEIPT
            header("Location: receipt.php");
            exit;

        } else {

            $orderMessage = $result['message'];

        }

    }

}


// REFRESH CART INFORMATION AFTER FORM SUBMISSION
$cartErrors = validateCartContents();

$cartItems = getCartItems();

$subtotal = calculateSubtotal();


// CALCULATE DISCOUNT
$discount = calculateDiscount($subtotal, $promoCode);


// CALCULATE FINAL TOTAL
$total = calculateTotal(
    $subtotal,
    $discount['discount_amount']
);


// LOAD SHARED HEADER
require_once __DIR__ . '/../Includes/header.php';

require_once __DIR__ . '/../Includes/navbar.php';

?>

<div class="cinema-page">

    <div class="container cinema-inner-page">

        <!-- PAGE HEADING -->
        <div class="page-heading mb-4">

            <span class="section-eyebrow">FINAL STEP</span>

            <h1>Secure Your Movie Tickets</h1>

            <p>
                Provide your information and review your order before confirming.
            </p>

        </div>


        <!-- VALIDATION ERRORS -->
        <?php if (!empty($errors) || !empty($cartErrors)): ?>

            <div class="alert alert-danger">

                <strong>Please check the following:</strong>

                <ul class="mb-0">

                    <?php foreach ($errors as $error): ?>

                        <li>
                            <?php echo htmlspecialchars($error); ?>
                        </li>

                    <?php endforeach; ?>


                    <?php foreach ($cartErrors as $error): ?>

                        <li>
                            <?php echo htmlspecialchars($error); ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>


        <!-- ORDER PROCESSING ERROR -->
        <?php if ($orderMessage != ""): ?>

            <div class="alert alert-danger">

                <i class="bi bi-exclamation-circle me-2"></i>

                <?php echo htmlspecialchars($orderMessage); ?>

            </div>

        <?php endif; ?>


        <?php if (empty($cartErrors) && !empty($cartItems)): ?>

            <div class="row g-4">

                <!-- CUSTOMER INFORMATION -->
                <div class="col-lg-7">

                    <div class="checkout-card">

                        <div class="checkout-card-heading">

                            <div class="checkout-icon">
                                <i class="bi bi-person-vcard"></i>
                            </div>

                            <div>
                                <h4>Customer Information</h4>

                                <p>
                                    Enter the details for your transaction.
                                </p>
                            </div>

                        </div>


                        <form method="POST" action="checkout.php" class="checkout-form">

                            <!-- CUSTOMER NAME -->
                            <div class="mb-4">

                                <label for="customer_name" class="form-label cinema-label">
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    name="customer_name"
                                    id="customer_name"
                                    class="form-control cinema-input"
                                    placeholder="Enter your full name"
                                    value="<?php echo htmlspecialchars($customerName); ?>"
                                    maxlength="100"
                                    required
                                >

                            </div>


                            <!-- CUSTOMER EMAIL -->
                            <div class="mb-4">

                                <label for="customer_email" class="form-label cinema-label">
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    name="customer_email"
                                    id="customer_email"
                                    class="form-control cinema-input"
                                    placeholder="Enter your email address"
                                    value="<?php echo htmlspecialchars($customerEmail); ?>"
                                    required
                                >

                            </div>


                            <!-- PROMOTIONAL CODE -->
                            <div class="mb-4">

                                <label for="promo_code" class="form-label cinema-label">
                                    Promotional Code
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text cinema-input-icon">
                                        <i class="bi bi-ticket-perforated"></i>
                                    </span>

                                    <input
                                        type="text"
                                        name="promo_code"
                                        id="promo_code"
                                        class="form-control cinema-input"
                                        placeholder="Enter your promotional code (optional)"
                                        value="<?php echo htmlspecialchars($promoCode); ?>"
                                    >

                                </div>

                                <small class="checkout-hint">

                                    <i class="bi bi-tag me-1"></i>
                                    Use CINE15 to receive a 15% discount.

                                </small>

                            </div>


                            <!-- SIMULATED PAYMENT NOTICE -->
                            <div class="checkout-notice mb-4">

                                <i class="bi bi-shield-check"></i>

                                <div>

                                    <strong>Simulated Transaction</strong>

                                    <p>
                                        This project uses a simulated checkout.
                                        No actual payment will be collected.
                                    </p>

                                </div>

                            </div>


                            <!-- PLACE ORDER -->
                            <button
                                type="submit"
                                class="btn btn-cinema-primary w-100 checkout-submit"
                            >

                                <i class="bi bi-check-circle me-2"></i>
                                Place Order

                            </button>

                        </form>

                    </div>

                </div>


                <!-- ORDER SUMMARY -->
                <div class="col-lg-5">

                    <div class="checkout-summary-card">

                        <span class="section-eyebrow">
                            YOUR PURCHASE
                        </span>

                        <h4>Order Summary</h4>

                        <p class="summary-description">
                            Your selected movie tickets.
                        </p>

                        <hr>


                        <!-- PURCHASED MOVIES -->
                        <?php foreach ($cartItems as $item): ?>

                            <div class="checkout-movie-item">

                                <div class="checkout-movie-details">

                                    <strong>
                                        <?php echo htmlspecialchars($item['title']); ?>
                                    </strong>

                                    <small>
                                        <?php echo (int) $item['quantity']; ?> ticket(s)
                                        × ₱<?php echo number_format($item['price'], 2); ?>
                                    </small>

                                </div>

                                <strong class="checkout-item-total">

                                    ₱<?php echo number_format($item['item_total'], 2); ?>

                                </strong>

                            </div>

                        <?php endforeach; ?>


                        <hr>


                        <!-- SUBTOTAL -->
                        <div class="summary-line">

                            <span>Subtotal</span>

                            <strong>
                                ₱<?php echo number_format($subtotal, 2); ?>
                            </strong>

                        </div>


                        <!-- DISCOUNT -->
                        <div class="summary-line">

                            <span>Discount</span>

                            <strong class="discount-value">

                                -₱<?php echo number_format($discount['discount_amount'], 2); ?>

                            </strong>

                        </div>


                        <!-- APPLIED DISCOUNT -->
                        <div class="applied-discount">

                            <i class="bi bi-tag-fill me-1"></i>

                            Selected Discount:

                            <strong>
                                <?php echo htmlspecialchars($discount['discount_name']); ?>
                            </strong>

                        </div>


                        <!-- PROMO CODE MESSAGE -->
                        <?php if ($discount['message'] != ""): ?>

                            <div class="alert alert-warning mt-3">

                                <?php echo htmlspecialchars($discount['message']); ?>

                            </div>

                        <?php endif; ?>


                        <hr>


                        <!-- FINAL TOTAL -->
                        <div class="summary-total">

                            <span>Final Total</span>

                            <h3>
                                ₱<?php echo number_format($total, 2); ?>
                            </h3>

                        </div>


                        <a href="cart.php" class="back-to-cart-link">

                            <i class="bi bi-arrow-left me-2"></i>
                            Return to Cart

                        </a>

                    </div>

                </div>

            </div>

        <?php else: ?>

            <!-- EMPTY OR INVALID CART -->
            <div class="empty-cart text-center">

                <div class="empty-cart-icon">
                    <i class="bi bi-cart-x"></i>
                </div>

                <h3>Your cart is empty or invalid.</h3>

                <p>
                    Please return to your cart and select your movies first.
                </p>

                <a href="cart.php" class="btn btn-cinema-primary mt-3">

                    <i class="bi bi-arrow-left me-2"></i>
                    Return to Cart

                </a>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php require_once __DIR__ . '/../Includes/footer.php'; ?>
