<?php
require_once('lib/words.php');
require_once('lib/places.php');

$viewData = array(
    'title' => "Link Phrases demo"
);


$err = "";
$a1 = $_GET["a1"];
$n1 = $_GET["n1"];
$a2 = $_GET["a2"];
$n2 = $_GET["n2"];
$id = $_GET["id"];


if (isset($a1) && isset($n1) && isset($a2) && isset($n2)) {
    $id = getIdFromMnemonic($a1, $n1, $a2, $n2);
    if ($id == -1) {
        $err = "Unable to generate an ID from the phrase provided";
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
        $viewData['title'] .= ': ' . $place->name;
    } catch (Exception $e) {
        $err = "Error accessing the Yahoo GeoPlanet service";
        // Do something here.
    }
}


?>

<?php include('partial/_start.php'); ?>

<nav class="mod mod-rm mod-rm-bg3 copy copy-small">
    <div class="inner">
        <p><a href="index.php">Home</a> &rarr; Search results</p>
    </div>
</nav>

<section class="mod mod-rm mod-rm-bg1" id="modResource">
    <div class="inner">
        <?php if ($err): ?>
            <div class="hd">
                <h1 class="h2"><?php echo $err ?></h1>
            </div>
        <?php else: ?>
            <?php if ($place): ?>
            <div class="hd">
                <h1 class="h2"><a href="resource.php?id=<?php echo $id?>"><?php echo $place->name. ' (' . $place->placeTypeName->content. '), ' . $place->country->content ?></a></h1>
            </div>
            <div class="bd copy">
                <!--
                <?php echo var_dump($place); ?>
                -->
                <p class="infobox">To return to this place page, you can copy the <a href=".">link in the address bar</a>, or
                you can visit the <a href="index.php">home page</a> and enter the link phrase <span class="linkphrase">"<?php echo getPhraseFromMnemonic(getMnemonicFromId($id))?>"</span>.</p>
                
                <div id="map_canvas" style="height:250px;width:100%;"></div>
                <p><?php echo $place->centroid->latitude ?>, <?php echo $place->centroid->longitude ?></p>
            </div>

            <?php else: ?>
            <div class="hd">
                <h1 class="h2">There is no place associated with the phrase "<?php echo getPhraseFromMnemonic(getMnemonicFromId($id))?>".</h1>
            </div>
            <div class="bd copy">
                <p><a href="index.php">Try another</a></p>
            </div>
            <?php endif ?>
        <?php endif ?>
    </div>
</section>

<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>
    function initMap() {
        var boundingBoxSW = new google.maps.LatLng(<?php echo $place->boundingBox->southWest->latitude ?>,<?php echo $place->boundingBox->southWest->longitude ?>);
        var boundingBoxNE = new google.maps.LatLng(<?php echo $place->boundingBox->northEast->latitude ?>,<?php echo $place->boundingBox->northEast->longitude ?>);
        var boundingBox = new google.maps.LatLngBounds(boundingBoxSW,boundingBoxNE);
        var centroid = new google.maps.LatLng(<?php echo $place->centroid->latitude ?>, <?php echo $place->centroid->longitude ?>);
        var opts = {
            center: centroid,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"), opts);
        map.fitBounds(boundingBox);
    }
    document.onload = initMap();
</script>

<?php include('partial/_end.php'); ?>
