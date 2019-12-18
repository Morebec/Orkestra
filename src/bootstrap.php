<?php

declare(strict_types=1);
require __DIR__ . '/../vendor/autoload.php';

// Polyfill for PHP7 < 7.3
if (!function_exists('array_key_first')) {
    function array_key_first(array $arr)
    {
        foreach ($arr as $key => $unused) {
            return $key;
        }
        return null;
    }
}
