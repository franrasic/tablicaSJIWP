<?php
echo '<pre>';
echo 'DOCUMENT_ROOT: ' . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo 'REQUEST_URI: ' . $_SERVER['REQUEST_URI'] . "\n";
echo 'Real path: ' . realpath(__DIR__) . "\n";
echo 'File exists: ' . (file_exists(__DIR__.'/proizvodi/uredi.php') ? 'Da' : 'Ne');