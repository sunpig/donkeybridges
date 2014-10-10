<?php
define('BASE', 100);

function adjectives() { 
    return array("angry","big","black","blue","bored","brave","broken","brown","clean","cold","crazy","crouching","dancing","dangerous","dirty","empty","falling","fast","flat","flying","fresh","friendly","frozen","full","funny","golden","green","hairy","happy","hard","heavy","hidden","hopping","hot","hungry","juicy","jumping","laughing","lazy","light","lucky","lumpy","metal","mysterious","narrow","naughty","noisy","old","orange","painted","pink","plastic","purple","quiet","rainy","red","rough","round","running","salty","scary","shaking","sharp","shiny","short","shy","silver","singing","skinny","sleeping","slippery","slow","small","smooth","soft","sour","spicy","spotted","square","squishy","sticky","strange","stripy","strong","strong","sunny","sweet","tall","thirsty","tiny","tired","weak","wet","white","wide","wild","wooden","wrinkled","yellow","young");
}

function nouns() {
    return  array("ant","apple","axe","backpack","bag","ball","banana","bat","bear","bee","bell","bike","bird","boat","book","bottle","box","bus","cake","camera","car","carrot","cat","chair","chicken","clock","cloud","cow","crown","cup","deer","dog","door","dragon","dress","duck","egg","elephant","feather","fire","fish","flag","flower","foot","fork","frog","ghost","giraffe","glove","hand","hat","heart","horse","house","jacket","key","king","knife","leaf","leg","lemon","lion","lizard","monkey","moon","mouse","mouth","nail","nose","owl","pen","phone","pie","pig","plane","plate","queen","rainbow","ring","rock","shark","sheep","shirt","shoe","snail","snake","spider","spoon","square","star","table","tent","tiger","tooth","train","tree","vase","wheel","worm","zebra");
}

function isVowel($letter) {
    return array_search($letter, array('a','e','i','o','u'))!==false;
}

function getOptions($list, $selectedWord = ""){
    $options = "";
    foreach($list as $word) {
        $selected = ($selectedWord == $word) ? "selected" : "";
        $options .= "<option value=\"$word\" $selected>$word</option>";
    }
    return $options;
}

// Returns false if the word is not found in the list
function getNumberFromWord($word, $list) {
    $number = array_search($word, $list);
    if ($number===false) {
        $number = -1;
    }
    return $number;
}

// Returns false if the number is outside the boundaries of the list
function getWordFromNumber($number, $list) {
    $word = "";
    if (($number >=0) && $number < count($list)) {
        $word = $list[$number];
    }
    return $word;
}

function getIdFromMnemonic($a1, $n1, $a2, $n2) {
    $id = -1;
    $adjectives = adjectives();
    $nouns = nouns();
    $ix1 = getNumberFromWord($a1, $adjectives);
    $ix2 = getNumberFromWord($n1, $nouns);
    $ix3 = getNumberFromWord($a2, $adjectives);
    $ix4 = getNumberFromWord($n2, $nouns);

    if (($ix1 >= 0) && ($ix2 >= 0) && ($ix3 >= 0) && ($ix4 >= 0)) {
        $id = getIdFromIndexes(array($ix1, $ix2, $ix3, $ix4));
    }

    return $id;
}

function getMnemonicFromId($id) {
    $adjectives = adjectives();
    $nouns = nouns();

    $arrIndexes = getIndexesFromId($id);
    $ix1 = $arrIndexes[0];
    $ix2 = $arrIndexes[1];
    $ix3 = $arrIndexes[2];
    $ix4 = $arrIndexes[3];

    $a1 = getWordFromNumber($ix1, $adjectives);
    $n1 = getWordFromNumber($ix2, $nouns);
    $a2 = getWordFromNumber($ix3, $adjectives);
    $n2 = getWordFromNumber($ix4, $nouns);

    return array($a1, $n1, $a2, $n2);
}

// Get the lookup indexes based on the resource ID
function getIndexesFromId($id) {
    $base_squared = pow(BASE,2);
    $base_cubed = pow(BASE,3);

    $ix4 = $id % BASE;

    $ix3 = (int)(($id % $base_squared) / BASE);
    $ix3 = (($ix3 + $base_squared) - (13 * $ix4)) % BASE;

    $ix2 = (int)(($id % $base_cubed) / $base_squared);
    $ix2 = (($ix2 + $base_squared) - (17 * $ix4)) % BASE;

    $ix1 = (int)($id / $base_cubed);
    $ix1 = (($ix1 + $base_squared) - (19 * $ix4)) % BASE;

    return array($ix1, $ix2, $ix3, $ix4);
}

// Get the resource ID from the lookup indexes
function getIdFromIndexes($arrIndexes) {
    $ix1 = $arrIndexes[0];
    $ix2 = $arrIndexes[1];
    $ix3 = $arrIndexes[2];
    $ix4 = $arrIndexes[3];

    $id = (pow(BASE,3) * (($ix1 + (19 * $ix4)) % BASE)) + (pow(BASE,2) * (($ix2 + (17 * $ix4)) % BASE)) + (BASE * (($ix3 + (13 * $ix4)) % BASE)) + $ix4;
    return $id;
}

function getPhraseFromMnemonic($arrMnemonic) {
    $a1 = $arrMnemonic[0];
    $n1 = $arrMnemonic[1];
    $a2 = $arrMnemonic[2];
    $n2 = $arrMnemonic[3];

    $phrase = isVowel(substr($a1, 0, 1)) ? "An " : "A ";
    $phrase .= "$a1 $n1 and ";
    $phrase .= isVowel(substr($a2, 0, 1)) ? "an " : "a ";
    $phrase .= "$a2 $n2";
    return $phrase;
}

?>