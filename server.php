<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// When the built-in server is started with the public directory as the docroot,
// static assets still live under __DIR__/public on disk. Check that location
// first so CSS/JS/images are served directly instead of being routed into Laravel.
$publicAssetPath = __DIR__.'/public'.$uri;
$rootAssetPath = __DIR__.$uri;

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && (file_exists($publicAssetPath) || file_exists($rootAssetPath))) {
    return false;
}

require_once __DIR__.'/index.php';
