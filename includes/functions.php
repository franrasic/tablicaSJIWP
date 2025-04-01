<?php
function getBaseUrl() {
    return sprintf(
        "%s://%s%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME'],
        dirname($_SERVER['SCRIPT_NAME'])
    );
}

function asset($path) {
    return getBaseUrl() . '/' . ltrim($path, '/');
}
?>