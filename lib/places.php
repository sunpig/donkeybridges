<?php

function getPlaceById($id){
    $place = null;
    $yqlQuery = 'select * from geo.places where woeid=' . $id;
    $yqlUrl = 'http://query.yahooapis.com/v1/public/yql?format=json&q=' . urlencode($yqlQuery);
    $response_json = get($yqlUrl);
    $response_object = json_decode($response_json);
    $query = $response_object->query;
    if ($query) {
        $results = $query->results;
        if ($results) {
            $place = $results->place;
        }
    }
    return $place;
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
            header("Location: resource.php?id=" . $query->results->place->woeid);
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

function get($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);
  return $output;
}

?>