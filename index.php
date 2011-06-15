<?php
require_once('lib/words.php');

$adjectives = adjectives();
$nouns = nouns();

$a1 = filter_input(INPUT_GET, "a1", FILTER_SANITIZE_STRING);
$n1 = filter_input(INPUT_GET, "n1", FILTER_SANITIZE_STRING);
$a2 = filter_input(INPUT_GET, "a2", FILTER_SANITIZE_STRING);
$n2 = filter_input(INPUT_GET, "n2", FILTER_SANITIZE_STRING);

if (getNumberFromWord($a1, $adjectives)==-1) {
    $a1 = getWordFromNumber(rand(0,99), $adjectives);
}
if (getNumberFromWord($n1, $nouns)==-1) {
    $n1 = getWordFromNumber(rand(0,99), $nouns);
}
if (getNumberFromWord($a2, $adjectives)==-1) {
    $a2 = getWordFromNumber(rand(0,99), $adjectives);
}
if (getNumberFromWord($n2, $nouns)==-1) {
    $n2 = getWordFromNumber(rand(0,99), $nouns);
}

$a1Options = getOptions($adjectives, $a1);
$n1Options = getOptions($nouns, $n1);
$a2Options = getOptions($adjectives, $a2);
$n2Options = getOptions($nouns, $n2);

$pageTitle = "Donkey Bridges"

?>

<?php include('partial/_start.php'); ?>

    <div class="line">
        <div class="unit size1of1 size-bp720-1of2">
            <section class="mod mod-rm mod-rm-light">
                <div class="inner mam">
                    <div class="hd">
                        <h2 class="h2">Enter a link phrase</h2>
                    </div>
                    <div class="bd">
                        <form action="place.php" method="get" class="copy">
                            <p>
                                <span class="nobr">A 
                                    <select name="a1"><?php echo $a1Options ?></select>
                                    <select name="n1"><?php echo $n1Options ?></select></span>
                                <span class="nobr">and a 
                                    <select name="a2"><?php echo $a2Options ?></select>
                                    <select name="n2"><?php echo $n2Options ?></select></span>
                            </p>
                            <p><input type="submit" value="Take me there!" /></p>
                            <p>Note: not every phrase has a place associated with it! <span class="secondary">The probability of finding a place with a random phrase is about 1 in 20.</span></p>
                        </form>
                    </div>
                </div>
            </section>
        </div>

        <div class="unit lastUnit size1of1 size-bp720-1of2">
            <section class="mod mod-rm mod-rm-light">
                <div class="inner mam">
                    <div class="hd">
                        <h1 class="h2">Or try these example phrases:</h1>
                    </div>
                    <div class="bd copy">
                        <ul>
                            <li><a href="place.php?a1=old&amp;n1=pie&amp;a2=dancing&amp;n2=spoon">An old pie and a dancing spoon</a></li>
                            <li><a href="place.php?a1=short&amp;n1=house&amp;a2=friendly&amp;n2=fork">A short house and a friendly fork</a></li>
                            <li><a href="place.php?a1=round&amp;n1=chicken&amp;a2=quiet&amp;n2=wheel">A round chicken and a quiet wheel</a></li>
                        </ul>
                    </div>
                </div>
            </section>

            <section class="mod mod-rm mod-rm-light">
                <div class="inner mam">
                    <div class="hd">
                        <h1 class="h2">Or search for a place</h1>
                    </div>
                    <div class="bd copy">
                        <form action="search.php" method="get">
                            <p>
                                <input type="search" id="placename" name="placename" placeholder="Place name" />
                                <input type="submit" value="Find it!" />
                            </p>
                        </form>
                    </div>
                </div>
            </section>

        </div>

    </div>

<?php include('partial/_end.php'); ?>

