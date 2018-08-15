<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->exclude(['views', 'vendor'])
    ->files()
    ->name('*.php')
;

return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([
        '@PSR2'                              => true,
        'psr4'                               => true,
        'no_unused_imports'                  => true,
        'array_syntax'                       => ['syntax' => 'short'],
        'blank_line_after_opening_tag'       => true,
        'linebreak_after_opening_tag'        => true,
        'no_blank_lines_after_class_opening' => true,
        'new_with_braces'                    => true,
        'blank_line_before_statement'        => true,
        'no_whitespace_in_blank_line'        => true,
        'phpdoc_trim'                        => true,
        'phpdoc_types'                       => true,
        'phpdoc_align'                       => true,
        'phpdoc_scalar'                      => true,
        'php_unit_strict'                    => true,
    ])
;
