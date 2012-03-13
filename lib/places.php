<?php

function getPlaceInfoById($id){
    $placeInfo = null;
    $yqlQuery= 'select * from yql.query.multi where queries = "'
      . 'select * from geo.places where woeid = ' . $id
      . ';select * from geo.places.neighbors(10) where neighbor_woeid = ' . $id
      . ';select * from flickr.photos.search(8) where has_geo=\'true\' and content_type=1 and media=\'photos\' and license in (4,2,7) and woe_id=' . $id . ' limit 8'
      . ';select * from flickr.people.info2(8) where user_id in (select owner from flickr.photos.search(8) where has_geo=\'true\' and content_type=1 and media=\'photos\' and license in (4,2,7) and woe_id=' . $id . ')'
      . ';select * from geo.places.children(10) where parent_woeid = ' . $id
      . '"';

    $yqlUrl = 'http://query.yahooapis.com/v1/public/yql?format=json&q=' 
                . urlencode($yqlQuery)
                . '&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';

    $response_json = get($yqlUrl);
    $response_object = json_decode($response_json);
    $query = $response_object->query;
    if ($query) {
        $results = $query->results;
        if ($results) {
            $placeInfo = $results;
        }
    }
    return $placeInfo;
}

function getPlacesByName($placename){
    $places = null;
    $yqlQuery = 'select * from geo.places where text="' . $placename . '"';
    $yqlUrl = 'http://query.yahooapis.com/v1/public/yql?format=json&q=' . urlencode($yqlQuery);
    $response_json = get($yqlUrl);
    $response_object = json_decode($response_json);
    $query = $response_object->query;
    if ($query) {
        if ($query->count == 0) {
            // Handle no results
        } else if ($query->count == 1) {
            // Redirect to result
            header("Location: place.php?id=" . $query->results->place->woeid);
            exit;            
        } else {
            $results = $query->results;
            if ($results) {
                $places = $results->place; // This is an array of places
            }
        }
    }

    return $places;
}

function formatPlaceWithAdmin1AndCountry($place) {
    $text = $place->name;
    if ($place->admin1 && ($place->name != $place->admin1->content)) {
        $text .= ', ' . $place->admin1->content;
    }
    if ($place->country && ($place->name != $place->country->content)) {
        $text .= ', ' . $place->country->content;
    }

    return $text;
}

function get($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);
  return $output;
}

?>