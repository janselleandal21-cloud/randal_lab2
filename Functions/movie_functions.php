<?php
require_once __DIR__ . '/../Data/movies.php';

// FUNCTION 1: GET ALL MOVIES
// Returns the complete movie catalog.
function getMovies() {
    global $movies;
    return $movies;
}

// FUNCTION 2: GET MOVIE BY ID
// Searches for a specific movie using its ID.
function getMovieById($movieId) {
    $movies = getMovies();
    foreach ($movies as $movie) {
        if ($movie["id"] == $movieId) {
            return $movie;
        }
    }
    return false;
}
?>
