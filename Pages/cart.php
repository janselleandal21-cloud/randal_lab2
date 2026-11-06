
<?php

require_once __DIR__ . '/../Includes/init.php';

require_once __DIR__ . '/../Functions/movie_functions.php';
require_once __DIR__ . '/../Functions/cart_functions.php';


// PROCESS CART ACTIONS
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'] ?? '';

    $movieId = filter_input(INPUT_POST, "movie_id", FILTER_VALIDATE_INT);

    if ($movieId === false || $movieId === null) {

        $_SESSION['message'] = "Invalid movie selection.";

    } else {

        // UPDATE QUANTITY
        if ($action == "update") {

            $quantity = filter_input(INPUT_POST, "quantity", FILTER_VALIDATE_INT);

            if ($quantity === false || $quantity === null) {

                $_SESSION['message'] = "Please enter a valid quantity.";

            } elseif (updateCart($movieId, $quantity)) {

                $_SESSION['message'] = "Cart quantity successfully updated.";

            } else {

                $_SESSION['message'] = "Invalid quantity or movie selection.";

            }

        // REMOVE MOVIE
        } elseif ($action == "remove") {

            if (removeFromCart($movieId)) {

                $_SESSION['message'] = "Movie successfully removed from your cart.";

            } else {

                $_SESSION['message'] = "Movie not found in your cart.";

            }

        } else {

            $_SESSION['message'] = "Invalid cart action.";

        }

    }

    // PREVENT DUPLICATE FORM SUBMISSION
    header("Location: cart.php");
    exit;

}


// GET CART INFORMATION
$cartItems = getCartItems();

$subtotal = calculateSubtotal();

$totalTickets = 0;

foreach ($cartItems as $item) {
    $totalTickets += $item['quantity'];
}

$pageTitle = "My Cart | Cinema Ticket System";

require_once __DIR__ . '/../Includes/header.php';
require_once __DIR__ . '/../Includes/navbar.php';

?>

<div class="cinema-page">

    <div class="container cinema-inner-page">

        <!-- PAGE HEADING -->
        <div class="page-heading mb-4">

            <span class="section-eyebrow">YOUR SELECTION</span>

            <h1>My Shopping Cart</h1>

            <p>
                Review your selected movies before proceeding to checkout.
            </p>

        </div>


        <!-- CART MESSAGE -->
        <?php if (isset($_SESSION['message'])): ?>

            <div class="alert cinema-alert alert-dismissible fade show">

                <i class="bi bi-info-circle me-2"></i>

                <?php echo htmlspecialchars($_SESSION['message']); ?>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"
                ></button>

            </div>

            <?php unset($_SESSION['message']); ?>

        <?php endif; ?>


        <?php if (empty($cartItems)): ?>

            <!-- EMPTY CART -->
            <div class="empty-cart text-center">

                <div class="empty-cart-icon">
                    <i class="bi bi-bag-x"></i>
                </div>

                <h3>Your cart is currently empty.</h3>

                <p>
                    You haven't selected any movies yet.
                    Explore our movie collection and find something to watch.
                </p>

                <a href="movies.php" class="btn btn-cinema-primary mt-3">

                    <i class="bi bi-film me-2"></i>
                    Browse Movies

                </a>

            </div>


        <?php else: ?>

            <div class="row g-4">

                <!-- CART ITEMS -->
                <div class="col-lg-8">

                    <div class="cart-section-header">

                        <div>
                            <h4>Selected Movies</h4>

                            <p>
                                <?php echo count($cartItems); ?>
                                movie item(s) in your cart
                            </p>
                        </div>

                        <span class="cart-ticket-count">

                            <i class="bi bi-ticket-perforated me-1"></i>

                            <?php echo $totalTickets; ?> Tickets

                        </span>

                    </div>


                    <?php foreach ($cartItems as $item): ?>

                        <div class="cart-item-card">

                            <div class="row g-3 align-items-center">

                                <!-- MOVIE POSTER -->
                                <div class="col-4 col-sm-3 col-md-3">

                                    <?php if (!empty($item['poster'])): ?>

                                        <img
                                            src="../<?php echo htmlspecialchars($item['poster']); ?>"
                                            alt="<?php echo htmlspecialchars($item['title']); ?>"
                                            class="cart-movie-poster"
                                        >

                                    <?php else: ?>

                                        <div class="cart-poster-placeholder">
                                            <i class="bi bi-film"></i>
                                        </div>

                                    <?php endif; ?>

                                </div>


                                <!-- MOVIE INFORMATION -->
                                <div class="col-8 col-sm-9 col-md-9">

                                    <div class="d-flex justify-content-between align-items-start gap-2">

                                        <div>

                                            <span class="cart-movie-genre">

                                                <?php echo htmlspecialchars($item['genre']); ?>

                                            </span>

                                            <h5 class="cart-movie-title mt-2">

                                                <?php echo htmlspecialchars($item['title']); ?>

                                            </h5>

                                            <p class="cart-movie-price">

                                                ₱<?php echo number_format($item['price'], 2); ?>

                                                <small>per ticket</small>

                                            </p>

                                        </div>

                                    </div>


                                    <div class="cart-item-bottom">

                                        <!-- UPDATE QUANTITY -->
                                        <form method="POST" action="cart.php" class="cart-quantity-form">

                                            <input
                                                type="hidden"
                                                name="movie_id"
                                                value="<?php echo (int) $item['id']; ?>"
                                            >

                                            <input
                                                type="hidden"
                                                name="action"
                                                value="update"
                                            >

                                            <label class="form-label">
                                                Quantity
                                            </label>

                                            <div class="d-flex gap-2">

                                                <input
                                                    type="number"
                                                    name="quantity"
                                                    class="form-control cinema-input"
                                                    value="<?php echo (int) $item['quantity']; ?>"
                                                    min="1"
                                                    max="10"
                                                    required
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-outline-gold"
                                                    title="Update quantity"
                                                >
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>

                                            </div>

                                        </form>


                                        <!-- ITEM TOTAL AND REMOVE -->
                                        <div class="cart-item-actions">

                                            <span class="small text-muted">
                                                Item Total
                                            </span>

                                            <h5 class="cart-item-total">

                                                ₱<?php echo number_format($item['item_total'], 2); ?>

                                            </h5>

                                            <form method="POST" action="cart.php">

                                                <input
                                                    type="hidden"
                                                    name="movie_id"
                                                    value="<?php echo (int) $item['id']; ?>"
                                                >

                                                <input
                                                    type="hidden"
                                                    name="action"
                                                    value="remove"
                                                >

                                                <button
                                                    type="submit"
                                                    class="btn btn-remove remove-item"
                                                >

                                                    <i class="bi bi-trash3 me-1"></i>
                                                    Remove

                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>


                    <a href="movies.php" class="continue-shopping-link">

                        <i class="bi bi-arrow-left me-2"></i>
                        Continue Shopping

                    </a>

                </div>


                <!-- ORDER SUMMARY -->
                <div class="col-lg-4">

                    <div class="cart-summary-card">

                        <span class="section-eyebrow">
                            PAYMENT OVERVIEW
                        </span>

                        <h4>Order Summary</h4>

                        <p class="summary-description">
                            Review your order total before checkout.
                        </p>

                        <hr>


                        <div class="summary-line">

                            <span>Movie Items</span>

                            <strong>
                                <?php echo count($cartItems); ?>
                            </strong>

                        </div>


                        <div class="summary-line">

                            <span>Total Tickets</span>

                            <strong>
                                <?php echo $totalTickets; ?>
                            </strong>

                        </div>


                        <div class="summary-line">

                            <span>Subtotal</span>

                            <strong>
                                ₱<?php echo number_format($subtotal, 2); ?>
                            </strong>

                        </div>


                        <hr>


                        <div class="summary-total">

                            <span>Estimated Total</span>

                            <h3>
                                ₱<?php echo number_format($subtotal, 2); ?>
                            </h3>

                        </div>


                        <p class="summary-note">

                            <i class="bi bi-info-circle me-1"></i>

                            Applicable discounts will be calculated during checkout.

                        </p>


                        <a
                            href="checkout.php"
                            class="btn btn-cinema-primary w-100 mt-3"
                        >

                            Proceed to Checkout
                            <i class="bi bi-arrow-right ms-2"></i>

                        </a>

                    </div>

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php require_once __DIR__ . '/../Includes/footer.php'; ?>
