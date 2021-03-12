<?php
    require_once 'load.php';

    if (isset($_GET['filter'])) {
        $filter = $_GET['filter'];
        $getMovies = getMoviesByGenre($filter);
    } else {
        $getMovies = getAllMovies();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the Movie CMS</title>
</head>

<body>
    <?php include 'templates/header.php';?>

    <?php foreach ($getMovies as $movie):?>
    <div class="movie-item">
        <img src="images/<?php echo $movie['movies_cover']; ?>"
            alt="<?php echo $movie['movies_title']; ?> Cover Image">
        <h2><?php echo $movie['movie_title']; ?>
        </h2>
        <h4>Movie Released: <?php echo $movie['movies_release']; ?>
        </h4>
        <h4>Movie Year: <?php echo $movie['movies_year']; ?>
        </h4>
        <p><?php echo $movie['movies_storyline']; ?>
        </p>
        <a href="#">Movie details..</a>
    </div>
    <?php endforeach;?>

    <?php include 'templates/footer.php';?>
</body>

</html>