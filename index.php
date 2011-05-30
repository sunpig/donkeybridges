<?php
require_once('lib/words.php');

$adjectives = adjectives();
$nouns = nouns();

$a1 = getWordFromNumber(rand(0,99), $adjectives);
$n1 = getWordFromNumber(rand(0,99), $nouns);
$a2 = getWordFromNumber(rand(0,99), $adjectives);
$n2 = getWordFromNumber(rand(0,99), $nouns);

$a1Options = getOptions($adjectives, $a1);
$n1Options = getOptions($nouns, $n1);
$a2Options = getOptions($adjectives, $a2);
$n2Options = getOptions($nouns, $n2);

$viewData = array(
    'title' => "Resource mnemonics (link phrases) demo"
);

?>

<?php include('partial/_start.php'); ?>

    <div class="pageBody yui3-g">
        <div class="yui3-u-1-2">
            <section><div class="section mod">
                <div class="inner">
                    <div class="bd">
                        <form action="search.php" method="get">
                            <p><label for="placename">Search for a place:</label> <input type="search" id="placename" name="placename" /></p>
                            <input type="submit" value="Find it" />
                        </form>
                    </div>
                </div>
            </div></section>
        </div>
        
        <div class="yui3-u-1-2">
            <section><div class="section mod">
                <div class="inner">
                    <div class="bd">
                        <form action="resource.php" method="get">
                            <p>Enter your link phrase</p>
                            <p>A 
                            <?php echo "
                            <select name=\"a1\">$a1Options</select>
                            <select name=\"n1\">$n1Options</select>
                            <br />and a 
                            <select name=\"a2\">$a2Options</select>
                            <select name=\"n2\">$n2Options</select>"
                            ?>
                            </p>
                            <input type="submit" value="Submit" />
                        </form>
                    </div>
                </div>
            </div></section>
        </div>
    </div>

<?php include('partial/_end.php'); ?>

