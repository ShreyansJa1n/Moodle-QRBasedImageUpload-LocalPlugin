<?php

require_once(__DIR__.'/../../config.php');
global $CFG;
global $PAGE;


$name = $CFG->dataroot.'/uploads/'.$_GET['img'];

$fp = fopen($name, 'rb');

header("Content-Type: image/jpg");
header("Content-Length: " . filesize($name));
fpassthru($fp);
exit;
?>