<?php 

// should require the composer autoloader on first
require __DIR__ . '/vendor/autoload.php';

use OneCMS\Tools\Fixer;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/test',
    ])
    ->name('*.php');

return (new Fixer($finder))
    ->getConfig()
    ->setUsingCache(false);