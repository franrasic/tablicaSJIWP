<?php
session_start();
echo '<pre>';
echo 'Sesija ID: ' . session_id() . "\n";
print_r($_SESSION);
print_r($_SERVER);
echo '</pre>';