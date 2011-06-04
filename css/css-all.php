<?php
$files = array(
    'webfonts.css',
    'lib/yui/reset-fonts.css',
    'lib/oocss/mod.css',
    'lib/oocss/grids.css',
    'lib/oocss/space.css',
    'headings.css',
    'layout.css',
    'mod-rm.css',
    'mod-rm-bg1.css',
    'mod-rm-bg2.css',
    'grids-mq.css'
);
$merged = '';
foreach($files as $file) {
	$merged .= file_get_contents($file) . "\n";
}

header("Content-Type: text/css");
header("Content-Length: " . strlen($merged));
echo $merged;
?>