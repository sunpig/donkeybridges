<?php
require_once('lib/words.php');
require_once('lib/places.php');

$err = "";
$placename = filter_input(INPUT_GET, "placename", FILTER_SANITIZE_STRING);

if (!isset($placename)) {
    $err = "You must enter a place name";
}

if (!$err) {
    try {
        $places = getPlacesByName($placename);
    } catch (Exception $e) {
        $err = "Error accessing the Yahoo GeoPlanet service";
        // Do something here.
    }
}

$pageTitle = "Donkey Bridges: search results";

?>

<?php include('partial/_start.php'); ?>

<section class="mod mod-rm mod-rm-light" id="modResource">
    <div class="inner mam">
        <?php if ($err): ?>
            <div class="hd">
                <h1 class="h2"><?php echo $err ?></h1>
            </div>
        <?php else: ?>
            <div class="hd">
                <h1 class="h2">Possible matches for “<?php echo $places[0]->name ?>”:</h1>
            </div>
            <div class="bd copy">
                <ul>
                    <?php 
                    foreach ($places as $place) {
                        echo '<li><a href="place.php?id=' . $place->woeid . '">' . formatPlaceWithAdmin1AndCountry($place) . '</a> (' . $place->placeTypeName->content . ')</li>';
                    }
                    ?>
                </ul>
            </div>
        <?php endif ?>
    </div>
</section>

<?php include('partial/_end.php'); ?>
