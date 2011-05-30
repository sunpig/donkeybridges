<?php
require_once('lib/words.php');
require_once('lib/places.php');

$err = "";
$a1 = $_GET["a1"];
$n1 = $_GET["n1"];
$a2 = $_GET["a2"];
$n2 = $_GET["n2"];
$id = $_GET["id"];


if (isset($a1) && isset($n1) && isset($a2) && isset($n2)) {
    $id = getIdFromMnemonic($a1, $n1, $a2, $n2);
    if ($id == -1) {
        $err = "Unable to generate an ID from the words provided";
    }
} else if (isset($id)) {
    // ID has been set directly
    if (is_numeric($id)) {
        $id = (int)$id;
        if (($id<0) || ($id>99999999)) {
            $err = "The ID must be between 0 and 99999999";
        }
    } else {
        $err = "The ID must be a whole number between 0 and 99999999";
    }
}

if (!$err) {
    $words = getMnemonicFromId($id);
    $place = null;
    try {
        $place = getPlaceById($id);
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
            echo "<p>ID is: $id</p>";
            echo "<p>Link phrase is: " . getPhraseFromMnemonic(getMnemonicFromId($id)) . "</p>";
            if ($place) {
                echo "<p>Represents " . $place->name. ' (' . $place->placeTypeName->content. '), country: ' . $place->country->content . '</p>';
            } else {
                echo "<p>There is no place associated with this phrase.</p>";
            }
        }
        ?>
        <p><a href="index.php">Try another</a></p>

<?php include('partial/_end.php'); ?>
