<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/src',
    ])

;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        '@PHP81Migration' => true,
        '@Symfony' => true,
        '@PSR12' => true,
        'concat_space' => [
            'spacing' => 'one',
        ],
        'types_spaces' => [
            'space' => 'single',
            'space_multiple_catch' => 'single',
        ],
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'no_unused_imports' => true,
    ])
    ->setFinder($finder)
;
