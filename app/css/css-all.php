<?php
$files = array(
    'lib/yui/reset-fonts.css',
    'lib/oocss/grids.css',
    'lib/oocss/mod.css',
    'lib/oocss/media.css',
    'lib/oocss/space.css',
    'fonts.css',
    'layout.css',
    'mod-rm.css',
    'grids-mq.css',
    'copy.css'
);
$merged = '';
foreach($files as $file) {
	$merged .= file_get_contents($file) . "\n";
}

header("Content-Type: text/css");
header("Content-Length: " . strlen($merged));
echo $merged;
?>