
<?php

require_once "../Includes/init.php";
require_once "../Functions/movie_functions.php";
require_once "../Functions/cart_functions.php";

$pageTitle = "Movies";

// GET MOVIES
$movies = getMovies();

// VARIABLES
$selectedQuantities = [];
$errors = [];

// DISPLAY SUCCESS MESSAGE
$successMessage = "";

if (isset($_SESSION["message"])) {
    $successMessage = $_SESSION["message"];
    unset($_SESSION["message"]);
}

// PROCESS SELECTED MOVIES
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_selected"])) {

    if (isset($_POST["quantities"]) && is_array($_POST["quantities"])) {

        $selectedMovies = [];

        foreach ($_POST["quantities"] as $movieId => $quantity) {

            $movieId = filter_var($movieId, FILTER_VALIDATE_INT);
            $quantity = filter_var($quantity, FILTER_VALIDATE_INT);

            if ($movieId === false || $quantity === false) {
                continue;
            }

            // KEEP SELECTED QUANTITY
            $selectedQuantities[$movieId] = $quantity;

            // SKIP MOVIES WITH ZERO QUANTITY
            if ($quantity === 0) {
                continue;
            }

            // CHECK IF MOVIE EXISTS
            $movie = getMovieById($movieId);

            if (!$movie) {
                $errors[] = "An invalid movie was selected.";
                continue;
            }

            // CHECK QUANTITY
            if ($quantity < 0 || $quantity > 10) {
                $errors[] = "Quantity for " . $movie["title"] . " must be between 0 and 10.";
                continue;
            }

            // CHECK EXISTING CART QUANTITY
            $currentQuantity = $_SESSION["cart"][$movieId] ?? 0;

            if (($currentQuantity + $quantity) > 10) {
                $errors[] = "You can only have a maximum of 10 tickets for " . $movie["title"] . ".";
                continue;
            }

            $selectedMovies[] = [
                "id" => $movieId,
                "quantity" => $quantity
            ];
        }

        // ADD ALL SELECTED MOVIES
        if (empty($errors) && !empty($selectedMovies)) {

            foreach ($selectedMovies as $selectedMovie) {

                addToCart(
                    $selectedMovie["id"],
                    $selectedMovie["quantity"]
                );

            }

            $_SESSION["message"] = "Selected movies have been added to your cart!";

            header("Location: cart.php");
            exit;

        } elseif (empty($selectedMovies) && empty($errors)) {

            $errors[] = "Please select at least one movie before adding to cart.";

        }

    } else {

        $errors[] = "Please select at least one movie.";

    }

}

include "../Includes/header.php";
include "../Includes/navbar.php";

?>

<div class="cinema-page">

    <!-- HERO SECTION -->
    <section class="cinema-hero">

        <div class="container">

            <div class="cinema-hero-content">

                <span class="section-eyebrow">
                    <i class="bi bi-stars"></i>
                    YOUR NEXT MOVIE EXPERIENCE
                </span>

                <h1>
                    Experience the Magic
                    <span>of Cinema.</span>
                </h1>

                <p>
                    Discover your favorite movies, choose your tickets,
                    and enjoy an unforgettable movie experience.
                </p>

                <a href="#movieCatalog" class="btn btn-gold cinema-oval-btn">
                    <i class="bi bi-ticket-perforated me-2"></i>
                    Browse Movies
                </a>

            </div>

        </div>

    </section>


    <!-- MOVIE CATALOG -->
    <section class="cinema-inner-page" id="movieCatalog">

        <div class="container">

            <!-- SUCCESS MESSAGE -->
            <?php if (!empty($successMessage)): ?>

                <div class="alert alert-success cinema-alert">

                    <i class="bi bi-check-circle-fill"></i>

                    <?php echo htmlspecialchars($successMessage); ?>

                    <a href="cart.php" class="alert-link ms-2">
                        View Cart
                    </a>

                </div>

            <?php endif; ?>


            <!-- ERROR MESSAGES -->
            <?php if (!empty($errors)): ?>

                <div class="alert alert-danger cinema-alert">

                    <i class="bi bi-exclamation-circle-fill"></i>

                    <ul class="mb-0 mt-2">

                        <?php foreach ($errors as $error): ?>

                            <li>
                                <?php echo htmlspecialchars($error); ?>
                            </li>

                        <?php endforeach; ?>

                    </ul>

                </div>

            <?php endif; ?>

            <!-- ONE FORM FOR ALL MOVIES -->
            <form method="POST" action="movies.php">
                <!-- SINGLE ADD BUTTON -->
                <div class="selected-movies-action mt-5">

                    <div class="selected-movies-message">

                        <i class="bi bi-cart-check"></i>

                        <div>
                            <strong>Ready to watch?</strong>

                            <p>
                                Review your selected quantities, then add
                                your movies to the cart.
                            </p>
                        </div>

                    </div>

                    <button
                        type="submit"
                        name="add_selected"
                        value="1"
                        class="btn btn-gold btn-lg add-selected-button"
                    >
                        <i class="bi bi-cart-plus"></i>
                        Add Selected Movies
                    </button>

                </div>

                <br>

                <!-- PAGE HEADING -->
                <div class="page-heading">

                    <div>
                        <span class="section-eyebrow">
                            NOW SHOWING
                        </span>

                        <h2>Explore Our Movies</h2>

                        <p>
                            Select your preferred movies and ticket quantities.
                            You can add multiple movies to your cart at once.
                        </p>
                    </div>

                    <div class="movie-count">
                        <i class="bi bi-collection-play"></i>
                        <?php echo count($movies); ?> Movies Available
                    </div>
                </div>

                <br>

                <div class="row g-4">

                    <?php foreach ($movies as $movie): ?>

                        <?php
                            $movieId = $movie["id"];
                            $quantity = $selectedQuantities[$movieId] ?? 0;
                        ?>

                        <div class="col-md-6 col-lg-4">

                            <div class="movie-card h-100">

                                <!-- MOVIE POSTER -->
                                <div class="movie-poster-wrapper">

                                    <?php if (!empty($movie["poster"])): ?>

                                        <img
                                            src="../<?php echo htmlspecialchars($movie["poster"]); ?>"
                                            alt="<?php echo htmlspecialchars($movie["title"]); ?> Poster"
                                            class="movie-poster"
                                        >

                                    <?php else: ?>

                                        <div class="movie-poster-placeholder">
                                            <i class="bi bi-film"></i>
                                        </div>

                                    <?php endif; ?>

                                    <span class="movie-genre-badge">
                                        <?php echo htmlspecialchars($movie["genre"]); ?>
                                    </span>

                                </div>


                                <!-- MOVIE DETAILS -->
                                <div class="movie-card-body">

                                    <h3 class="movie-title">
                                        <?php echo htmlspecialchars($movie["title"]); ?>
                                    </h3>

                                    <div class="movie-meta">

                                        <?php if (!empty($movie["duration"])): ?>

                                            <span>
                                                <i class="bi bi-clock"></i>
                                                <?php echo htmlspecialchars($movie["duration"]); ?>
                                            </span>

                                        <?php endif; ?>

                                        <?php if (!empty($movie["rating"])): ?>

                                            <span>
                                                <i class="bi bi-person-badge"></i>
                                                <?php echo htmlspecialchars($movie["rating"]); ?>
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                    <p class="movie-description">
                                        <?php echo htmlspecialchars($movie["description"]); ?>
                                    </p>


                                    <!-- PRICE AND QUANTITY -->
                                    <div class="movie-card-bottom">

                                        <div class="movie-price">

                                            <span>Ticket Price</span>

                                            <strong>
                                                ₱<?php echo number_format($movie["price"], 2); ?>
                                            </strong>

                                        </div>

                                        <div class="quantity-selection">

                                            <label
                                                for="quantity_<?php echo $movieId; ?>"
                                                class="form-label"
                                            >
                                                Quantity
                                            </label>

                                            <input
                                                type="number"
                                                id="quantity_<?php echo $movieId; ?>"
                                                name="quantities[<?php echo $movieId; ?>]"
                                                class="form-control cinema-quantity-input"
                                                value="<?php echo htmlspecialchars((string) $quantity); ?>"
                                                min="0"
                                                max="10"
                                            >

                                            <small>
                                                Enter 0 to skip
                                            </small>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            </form>

        </div>

    </section>

</div>

<?php include "../Includes/footer.php"; ?>
