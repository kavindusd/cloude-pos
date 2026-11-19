<?php

/**
 * PHP Built-in Server Router Script
 * This script handles rewriting for the PHP built-in server.
 */

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = urldecode($uri);

$requestedFile = __DIR__ . '/public' . $uri;

// If the file exists and is not a directory, serve it as is
if ($uri !== '/' && file_exists($requestedFile) && !is_dir($requestedFile)) {
    return false;
}

// Otherwise, route everything to public/index.php
require __DIR__ . '/public/index.php';
