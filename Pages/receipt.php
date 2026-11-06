
<?php

require_once __DIR__ . '/../Includes/init.php';


// CHECK IF A COMPLETED ORDER EXISTS
if (!isset($_SESSION['last_order']) || !is_array($_SESSION['last_order'])) {

    header("Location: movies.php");
    exit;

}


// RETRIEVE COMPLETED ORDER
$order = $_SESSION['last_order'];


// RETRIEVE CUSTOMER INFORMATION
$customer = $order['customer'];


// RETRIEVE PURCHASED MOVIES
$items = $order['items'];


// RETRIEVE TRANSACTION INFORMATION
$orderId = $order['order_id'];
$orderDate = $order['order_date'];

$subtotal = $order['subtotal'];
$discount = $order['discount'];
$discountName = $order['discount_name'];

$total = $order['total'];

$orderStatus = $order['order_status'];
$paymentStatus = $order['payment_status'];


$pageTitle = "Transaction Receipt | Cinema Ticket System";

require_once __DIR__ . '/../Includes/header.php';
require_once __DIR__ . '/../Includes/navbar.php';

?>

<div class="cinema-page">

    <div class="container cinema-inner-page">

        <!-- SUCCESS MESSAGE -->
        <div class="receipt-success text-center">

            <div class="receipt-success-icon">
                <i class="bi bi-check-lg"></i>
            </div>

            <span class="section-eyebrow">TRANSACTION COMPLETED</span>

            <h1>Order Successfully Processed!</h1>

            <p>
                Thank you for choosing our Cinema Ticket System.
                Your transaction details are shown below.
            </p>

        </div>


        <!-- RECEIPT -->
        <div class="receipt-paper" id="receipt">

            <!-- RECEIPT HEADER -->
            <div class="receipt-header text-center">

                <div class="receipt-logo">
                    <i class="bi bi-film"></i>
                </div>

                <h2>CINEMA TICKET SYSTEM</h2>

                <p>OFFICIAL TRANSACTION RECEIPT</p>

                <span class="receipt-status-badge">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    TRANSACTION SUCCESSFUL
                </span>

            </div>


            <div class="receipt-divider"></div>


            <!-- ORDER INFORMATION -->
            <div class="receipt-order-info">

                <div>

                    <small>ORDER IDENTIFICATION</small>

                    <h5>
                        <?php echo htmlspecialchars($orderId); ?>
                    </h5>

                </div>

                <div>

                    <small>TRANSACTION DATE</small>

                    <h6>
                        <?php echo htmlspecialchars($orderDate); ?>
                    </h6>

                </div>

            </div>


            <div class="receipt-divider"></div>


            <!-- CUSTOMER INFORMATION -->
            <div class="receipt-section">

                <span class="receipt-section-label">
                    CUSTOMER INFORMATION
                </span>

                <div class="receipt-detail-row">

                    <span>Customer Name</span>

                    <strong>
                        <?php echo htmlspecialchars($customer['name']); ?>
                    </strong>

                </div>

                <div class="receipt-detail-row">

                    <span>Email Address</span>

                    <strong>
                        <?php echo htmlspecialchars($customer['email']); ?>
                    </strong>

                </div>

            </div>


            <div class="receipt-divider"></div>


            <!-- PURCHASED MOVIES -->
            <div class="receipt-section">

                <span class="receipt-section-label">
                    PURCHASED MOVIES
                </span>

                <div class="table-responsive">

                    <table class="table receipt-table align-middle">

                        <thead>

                            <tr>

                                <th>Movie</th>

                                <th class="text-center">Quantity</th>

                                <th class="text-end">Amount</th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($items as $item): ?>

                                <tr>

                                    <td>

                                        <strong>
                                            <?php echo htmlspecialchars($item['title']); ?>
                                        </strong>

                                        <small>
                                            ₱<?php echo number_format($item['price'], 2); ?> each
                                        </small>

                                    </td>

                                    <td class="text-center">
                                        <?php echo (int) $item['quantity']; ?>
                                    </td>

                                    <td class="text-end fw-bold">

                                        ₱<?php echo number_format($item['item_total'], 2); ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>


            <div class="receipt-divider"></div>


            <!-- PAYMENT SUMMARY -->
            <div class="receipt-payment">

                <div class="receipt-detail-row">

                    <span>Subtotal</span>

                    <strong>
                        ₱<?php echo number_format($subtotal, 2); ?>
                    </strong>

                </div>


                <div class="receipt-detail-row">

                    <span>Discount</span>

                    <strong class="discount-value">
                        -₱<?php echo number_format($discount, 2); ?>
                    </strong>

                </div>


                <div class="receipt-discount-name">

                    Applied Discount:

                    <strong>
                        <?php echo htmlspecialchars($discountName); ?>
                    </strong>

                </div>


                <div class="receipt-total">

                    <span>FINAL TOTAL</span>

                    <h2>
                        ₱<?php echo number_format($total, 2); ?>
                    </h2>

                </div>

            </div>


            <div class="receipt-divider"></div>


            <!-- TRANSACTION STATUS -->
            <div class="receipt-status-section">

                <div>

                    <small>ORDER STATUS</small>

                    <span class="receipt-status-text">
                        <?php echo htmlspecialchars($orderStatus); ?>
                    </span>

                </div>

                <div>

                    <small>PAYMENT STATUS</small>

                    <span class="receipt-status-text">
                        <?php echo htmlspecialchars($paymentStatus); ?>
                    </span>

                </div>

            </div>


            <!-- RECEIPT FOOTER -->
            <div class="receipt-footer text-center">

                <i class="bi bi-ticket-perforated"></i>

                <h5>Thank You for Watching!</h5>

                <p>
                    We appreciate your purchase.
                    Enjoy your movie experience!
                </p>

                <small>
                    This is a simulated transaction receipt.
                    No actual payment was collected.
                </small>

            </div>

        </div>


        <!-- RECEIPT ACTIONS -->
        <div class="receipt-actions d-flex flex-wrap justify-content-center gap-3 mt-4">

            <button
                type="button"
                id="printReceipt"
                class="btn btn-cinema-primary"
            >

                <i class="bi bi-printer me-2"></i>
                Print Receipt

            </button>


            <a href="movies.php" class="btn btn-outline-gold">

                <i class="bi bi-film me-2"></i>
                Back to Movies

            </a>

        </div>

    </div>

</div>

<?php require_once __DIR__ . '/../Includes/footer.php'; ?>
