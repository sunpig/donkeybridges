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
    $a1 = $words[0];
    $n1 = $words[1];
    $a2 = $words[2];
    $n2 = $words[3];
    $placeInfo = null;
    $place = null;
    try {
        $placeInfo = getPlaceInfoById($id);
        $place = $placeInfo->results[0]->place;
        $viewData['title'] .= ': ' . $place->name;
        $phrase = getPhraseFromMnemonic(getMnemonicFromId($id));
    } catch (Exception $e) {
        $err = "Error accessing the Yahoo GeoPlanet service";
        // Do something here.
    }
}


?>

<?php include('partial/_start.php'); ?>

<nav class="mod mod-rm mod-rm-nav copy">
    <div class="inner mvn mhm">
        <p class="small"><a href="index.php">Home</a> &rarr; Place</p>
    </div>
</nav>

<section class="mod mod-rm mod-rm-dark" id="modResource">
    <div class="inner mam">
        <?php if ($err): ?>
            <div class="hd">
                <h1 class="h2"><?php echo $err ?></h1>
            </div>
        <?php else: ?>
            <?php if ($place): ?>
            <div class="hd">
                <h1 class="h2"><?php echo $place->name ?></h1>
                <?php
                if ($place->admin2 && ($place->name != $place->admin2->content)) {
                    echo '<h2 class="h4">' . $place->admin2->type . ': <a href="search.php?placename=' . urlencode($place->admin2->content) . '">' . $place->admin2->content . '</a></h2>';
                }
                if ($place->admin1 && ($place->name != $place->admin1->content)) {
                    echo '<h2 class="h4">' . $place->admin1->type . ': <a href="search.php?placename=' . urlencode($place->admin1->content) . '">' . $place->admin1->content . '</a></h2>';
                }
                if ($place->country && ($place->name != $place->country->content)) {
                    echo '<h2 class="h4">' . $place->country->type . ': <a href="search.php?placename=' . urlencode($place->country->content) . '">' . $place->country->content . '</a></h2>';
                }
                ?>
            </div>
            <div class="bd copy">
                <!--
                <?php echo var_dump($placeInfo) ?>
                -->
                
                <div class="copy">
                    <p>To return to this place page, enter the link phrase 
                        <a href="index.php?<?php echo "a1=$a1&n1=$n1&a2=$a2&n2=$n2" ?>" class="linkphrase">"<?php echo $phrase?>"</a> 
                        on the <a href="index.php?<?php echo "a1=$a1&n1=$n1&a2=$a2&n2=$n2" ?>">home page</a>.
                    </p>
                </div>

                <div id="twitterContainer"></div>

                <?php if ($placeInfo->results[2]): ?>
                    <h3 class="mtl mbs"><a href="http://creativecommons.org/">Creative Commons</a> licensed photos on <a href="http://www.flickr.com/">Flickr</a>:</h3>
                    <div>
                    <?php 
                    if (count($placeInfo->results[2]->photo) > 1) {
                        $placePhotos = $placeInfo->results[2]->photo;
                    } else {
                        $placePhotos = array($placeInfo->results[2]->photo);
                    }
                    
                    // Create a lookup map for Flickr users
                    if (count($placeInfo->results[3]->person) > 1) {
                        $people = $placeInfo->results[3]->person;
                    } else {
                        $people = array($placeInfo->results[3]->person);
                    }
                    $peopleMap = array();
                    foreach($people as $person) {
                        $peopleMap[$person->id] = $person;
                    }

                    foreach($placePhotos as $photo) {
                        $photoUrl = 'http://www.flickr.com/photos/' . $photo->owner . '/' . $photo->id;
                        echo '<figure class="media mhn">';
                        echo '<a href="' . $photoUrl . '" class="img mrm"><img class="flickrthumb" src="http://farm' . $photo->farm . '.static.flickr.com/' . $photo->server . '/' . $photo->id . '_' . $photo->secret . '_s.jpg" title="' . $photo->title . '" /></a>';
                        echo '<figcaption class="bd mlm"><p><a href="' . $photoUrl . '">' . $photo->title . '</a> by <a href="' . $peopleMap[$photo->owner]->profileurl . '">' . $peopleMap[$photo->owner]->username . '</a></p></figcaption>';
                        echo '</figure>';
                    }
                    ?>
                </div>
                    <ul>
                        <li><a href="http://www.flickr.com/places/<?php echo $place->woeid ?>">See more photos for <?php echo $place->name ?> on Flickr</a></li>
                        <li><a href="http://www.flickr.com/places/info/<?php echo $place->woeid ?>">See Flickr's geographic info page for <?php echo $place->name ?></a></li>
                    </ul>
                <?php endif ?>

                <div id="mapContainer"></div>

                <?php if ($placeInfo->results[1]): ?>
                    <h3 class="mtl mbs">Nearby places:</h3>
                    <ul>
                        <?php 
                        /* This may be a list or a single item. See http://www.wait-till-i.com/2010/09/22/the-annoying-thing-about-yqls-json-output-and-the-reason-for-it/ */
                        if (count($placeInfo->results[1]->place) > 1) {
                            $nearbyPlaces = $placeInfo->results[1]->place;
                        } else {
                            $nearbyPlaces = array($placeInfo->results[1]->place);
                        }
                        foreach($nearbyPlaces as $nearbyPlace) {
                            echo '<li><a href="resource.php?id=' . $nearbyPlace->woeid . '">' . formatPlaceWithAdmin1AndCountry($nearbyPlace) . '</a></li>';
                        }
                        ?>
                    </ul>
                <?php endif ?>

            </div>

            <?php else: ?>
            <div class="hd">
                <?php if (isset($a1) && isset($n1) && isset($a2) && isset($n2)): ?>
                    <h1 class="h2">There is no place associated with the phrase "<?php echo $phrase?>".</h1>
                <?php else: ?>
                    <h1 class="h2">There is no place associated with the WOEID <?php echo $id ?>.</h1>
                <?php endif ?>
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
    function init() {
        initMap();
        initTwitterIntents();
    }
    
    function onTweetClick(e) {
        e = e || window.event
        var target = e.target || e.srcElement;
        window.open(target.href, 'intent', windowOptions + ',width=' + width +
                                           ',height=' + height + ',left=' + left + ',top=' + top);
        if (window.addEventListener) {
          window.addEventListener("storage", handle_storage, false);
        } else {
          window.attachEvent("onstorage", handle_storage);
        };
    }
    
    function initTwitterIntents() {
        var twitterContainer = document.getElementById('twitterContainer');
        
        twitterContainer.innerHTML = [
            '<p>Have you found a cool place, or a particularly interesting link phrase? ',
            '<a class="tweet" href="http://twitter.com/intent/tweet?',
            'text=Where in the world is “<?php echo $phrase ?>”? Go to http://donkeybridges.com/ to find out.">',
            'Tweet it!</a></p>'
        ].join('');
    }

    function initMap() {
        var mapContainer = document.getElementById('mapContainer');
            boundingBoxSW = new google.maps.LatLng(<?php echo $place->boundingBox->southWest->latitude ?>,<?php echo $place->boundingBox->southWest->longitude ?>),
            boundingBoxNE = new google.maps.LatLng(<?php echo $place->boundingBox->northEast->latitude ?>,<?php echo $place->boundingBox->northEast->longitude ?>),
            boundingBox = new google.maps.LatLngBounds(boundingBoxSW,boundingBoxNE),
            centroid = new google.maps.LatLng(<?php echo $place->centroid->latitude ?>, <?php echo $place->centroid->longitude ?>),
            opts = {
                center: centroid,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            },
            marker = new google.maps.Marker({
                position:centroid,
                title:"<?php echo $place->name ?>"
            }),
            map = null;

        // Create map canvas
        mapContainer.innerHTML = [
            '<h3 class="mtl mbs">Map coordinates: <?php echo $place->centroid->latitude ?>, <?php echo $place->centroid->longitude ?></h3>',
            '<div id="map_canvas" style="height:250px;width:100%;"></div>'
        ].join('');

        // Create map
        map = new google.maps.Map(document.getElementById("map_canvas"), opts);
        // Fit to bounds, and place marker
        map.fitBounds(boundingBox);
        marker.setMap(map);
    }
    
    
    document.onload = init();
</script>

<?php include('partial/_end.php'); ?>
