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

<section class="mod mod-rm mod-rm-bg1" id="modResource">
    <div class="inner">
        <?php if ($err): ?>
            <div class="hd">
                <h1 class="h2"><?php echo $err ?></h1>
            </div>
        <?php else: ?>
            <div class="hd">
                <h1 class="h2">Possible matches:</h1>
            </div>
            <div class="bd">
                <ul>
                    <?php 
                    foreach ($places as $place) {
                        echo '<li><a href="resource.php?id=' . $place->woeid . '">' . $place->name. '</a> (' . $place->placeTypeName->content . '), country: ' . $place->country->content . ' <span class="mnemonic">' . getPhraseFromMnemonic(getMnemonicFromId($place->woeid)) . '</span></li>';
                    }
                    ?>
                </ul>
            </div>
        <?php endif ?>
    </div>
</section>

<?php include('partial/_end.php'); ?>
