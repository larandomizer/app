<?php

namespace PhpCsFixer;

$cacheDir = getenv('TRAVIS') ? getenv('HOME').'/.php-cs-fixer' : __DIR__;

return Config::create()
    ->setUsingCache(true)
    ->setCacheFile($cacheDir.'/.php_cs.cache')
    ->setRules([
        '@PSR2' => true,
        '@Symfony' => true,
        'no_multiline_whitespace_before_semicolons' => true,
        'not_operator_with_space' => true,
        'ordered_imports' => true,
        'phpdoc_order' => true,
        'psr0' => false,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'binary_operator_spaces' => [
            'align_double_arrow' => true,
        ],
    ])
    ->setFinder(
        Finder::create()
            ->in(__DIR__.'/app')
            ->in(__DIR__.'/config')
            ->in(__DIR__.'/database')
            ->in(__DIR__.'/tests')
    );
