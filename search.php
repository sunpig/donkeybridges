<?php
require_once('lib/words.php');
require_once('lib/places.php');

$err = "";
$placename = $_GET["placename"];

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

$viewData = array(
    'title' => "Resource mnemonics (link phrases) demo"
);

?>

<?php include('partial/_start.php'); ?>

        <?php
        if ($err) {
            echo $err;
        } else {
            echo "<p>Possible matches:</p><ul>";
            foreach ($places as $place) {
                echo '<li><a href="resource.php?id=' . $place->woeid . '">' . $place->name. '</a> (' . $place->placeTypeName->content . '), country: ' . $place->country->content . ' <span class="mnemonic">' . getPhraseFromMnemonic(getMnemonicFromId($place->woeid)) . '</span></li>';
            }
            echo "</ul>";
        }
        ?>
        <p><a href="index.php">Try again</a></p>

<?php include('partial/_end.php'); ?>
