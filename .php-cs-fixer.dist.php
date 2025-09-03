<?php

/*
 * PHP-CS-Fixer configuration for this project.
 *
 * Focus: PSR-12 style, PHP 8.1 compatibility, and readable wrapping.
 */

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->name('*.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setRules([
        '@PSR12' => true,
        '@PHP81Migration' => true,
        '@PHP80Migration:risky' => true,

        // Common readability tweaks
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'align_single_space_minimal'],
        'concat_space' => ['spacing' => 'one'],
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
            'keep_multiple_spaces_after_comma' => false,
        ],
        'multiline_comment_opening_closing' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'function', 'const'],
        ],
        'no_unused_imports' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'curly_brace_block', 'extra', 'parenthesis_brace_block',
                'square_brace_block', 'throw', 'use',
            ],
        ],
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_order' => true,
        'phpdoc_separation' => false,
        'phpdoc_trim' => true,
        'single_line_throw' => false,
        'single_quote' => true,
        'trailing_comma_in_multiline' => ['elements' => ['arrays']],
        'yoda_style' => false,
    ]);

