
<nav class="navbar navbar-expand-lg cinema-navbar">
    <div class="container">

        <!-- BRAND -->
        <a class="navbar-brand cinema-brand" href="movies.php">
            <span class="brand-icon">
                <i class="bi bi-film"></i>
            </span>
            <span>
                CiNEMA<span class="brand-dot">.</span>
                <small class="brand-subtitle">ticket management</small>
            </span>
        </a>

        <!-- MOBILE TOGGLER -->
        <button
            class="navbar-toggler cinema-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <i class="bi bi-list"></i>
        </button>

        <!-- NAVIGATION LINKS -->
        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <!-- MOVIES -->
                <li class="nav-item">
                    <a class="nav-link cinema-nav-link" href="movies.php">
                        <i class="bi bi-camera-reels"></i>
                        <span>Movies</span>
                    </a>
                </li>

                <!-- CART -->
                <li class="nav-item">
                    <a class="nav-link cinema-nav-link cinema-cart-link" href="cart.php">
                        <i class="bi bi-cart3"></i>
                        <span>My Cart</span>

                        <?php
                            $cartCount = 0;

                            if (isset($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $quantity) {
                                    $cartCount += $quantity;
                                }
                            }
                        ?>

                        <span class="cinema-cart-badge">
                            <?php echo $cartCount; ?>
                        </span>
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>
