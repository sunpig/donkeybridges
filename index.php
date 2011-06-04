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
    'title' => "Link Phrases demo"
);

?>

<?php include('partial/_start.php'); ?>

    <div class="line">
        <div class="unit size1of1 size-bp720-1of2 h100">
            <section class="mod mod-rm mod-rm-bg1 h100" id="modSearchByPlace">
                <div class="inner h100">
                    <div class="hd">
                        <h1 class="h2">Search for a place</h1>
                    </div>
                    <div class="bd copy h100">
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

        <div class="unit lastUnit size1of1 size-bp720-1of2">
            <section class="mod mod-rm mod-rm-bg1" id="modSearchByPhrase">
                <div class="inner">
                    <div class="hd">
                        <h2 class="h2">Or enter a <a href="#">link phrase</a></h2>
                    </div>
                    <div class="bd copy">
                        <form action="resource.php" method="get">
                            <p class="b">
                                <span class="nobr">A 
                                    <select name="a1"><?php echo $a1Options ?></select>
                                    <select name="n1"><?php echo $n1Options ?></select></span>
                                <span class="nobr">and a 
                                    <select name="a2"><?php echo $a2Options ?></select>
                                    <select name="n2"><?php echo $n2Options ?></select></span>
                            </p>
                            <p>
                                <input type="submit" value="Take me there!" />
                            </p>
                        </form>
                    </div>
                </div>
            </section>
        </div>

    </div>

<?php include('partial/_end.php'); ?>

