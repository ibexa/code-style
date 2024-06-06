<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\CodeStyle\PhpCsFixer\Sets;

use Ibexa\CodeStyle\PhpCsFixer\Sets\AbstractIbexaRuleSet;
use Ibexa\CodeStyle\PhpCsFixer\Sets\Ibexa50RuleSet;
use PhpCsFixer\RuleSet\RuleSet;
use PHPUnit\Framework\TestCase;

final class Ibexa50RuleSetTest extends TestCase
{
    public function testGetLocalRules(): void
    {
        $expectedLocalRules = [
            '@PER-CS2.0' => true,
            '@PSR12' => false,
            'AdamWojs/phpdoc_force_fqcn_fixer' => true,
            'array_syntax' => [
                'syntax' => 'short',
            ],
            'binary_operator_spaces' => true,
            'blank_line_after_namespace' => true,
            'blank_line_after_opening_tag' => false,
            'blank_line_before_statement' => [
                'statements' => [
                    'return',
                ],
            ],
            'blank_lines_before_namespace' => true,
            'cast_spaces' => false,
            'class_attributes_separation' => [
                'elements' => [
                    'method' => 'one',
                    'property' => 'one',
                ],
            ],
            'class_definition' => [
                'single_item_single_line' => true,
                'inline_constructor_arguments' => false,
            ],
            'concat_space' => [
                'spacing' => 'one',
            ],
            'constant_case' => [
                'case' => 'lower',
            ],
            'declare_equal_normalize' => true,
            'declare_strict_types' => true,
            'dir_constant' => true,
            'elseif' => true,
            'encoding' => true,
            'ereg_to_preg' => true,
            'error_suppression' => true,
            'fopen_flag_order' => true,
            'fopen_flags' => false,
            'full_opening_tag' => true,
            'function_declaration' => true,
            'function_to_constant' => true,
            'general_phpdoc_tag_rename' => true,
            'header_comment' => [
                'comment_type' => 'PHPDoc',
                'header' => AbstractIbexaRuleSet::IBEXA_PHP_HEADER,
                'location' => 'after_open',
                'separate' => 'top',
            ],
            'implode_call' => true,
            'include' => true,
            'increment_style' => true,
            'indentation_type' => true,
            'is_null' => true,
            'line_ending' => true,
            'lowercase_cast' => true,
            'lowercase_keywords' => true,
            'lowercase_static_reference' => true,
            'magic_constant_casing' => true,
            'magic_method_casing' => true,
            'method_argument_space' => true,
            'modernize_types_casting' => true,
            'native_constant_invocation' => [
                'fix_built_in' => false,
                'include' => [
                    'DIRECTORY_SEPARATOR',
                    'PHP_SAPI',
                    'PHP_VERSION_ID',
                ],
                'scope' => 'namespaced',
            ],
            'native_function_casing' => true,
            'native_function_invocation' => false,
            'no_alias_functions' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_blank_lines_after_phpdoc' => true,
            'no_break_comment' => false,
            'no_closing_tag' => true,
            'no_empty_comment' => true,
            'no_empty_phpdoc' => true,
            'no_empty_statement' => true,
            'no_extra_blank_lines' => [
                'tokens' => [
                    'curly_brace_block',
                    'extra',
                    'parenthesis_brace_block',
                    'square_brace_block',
                    'throw',
                    'use',
                ],
            ],
            'no_homoglyph_names' => true,
            'no_leading_import_slash' => true,
            'no_leading_namespace_whitespace' => true,
            'no_mixed_echo_print' => true,
            'no_multiline_whitespace_around_double_arrow' => true,
            'no_short_bool_cast' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_spaces_after_function_name' => true,
            'no_spaces_around_offset' => true,
            'no_trailing_comma_in_singleline' => [
                'elements' => [
                    'arguments',
                    'array_destructuring',
                    'array',
                    'group_import',
                ],
            ],
            'no_trailing_whitespace' => true,
            'no_trailing_whitespace_in_comment' => true,
            'no_unneeded_control_parentheses' => true,
            'no_unneeded_final_method' => true,
            'no_unused_imports' => true,
            'no_whitespace_before_comma_in_array' => true,
            'no_whitespace_in_blank_line' => true,
            'non_printable_character' => true,
            'normalize_index_brace' => true,
            'object_operator_without_whitespace' => true,
            'ordered_imports' => true,
            'php_unit_construct' => true,
            'php_unit_fqcn_annotation' => true,
            'php_unit_mock_short_will_return' => false,
            'php_unit_test_case_static_method_calls' => [
                'call_type' => 'self',
            ],
            'phpdoc_align' => false,
            'phpdoc_annotation_without_dot' => false,
            'phpdoc_indent' => true,
            'phpdoc_inline_tag_normalizer' => true,
            'phpdoc_no_access' => true,
            'phpdoc_no_alias_tag' => [
                'replacements' => [
                    'type' => 'var',
                    'link' => 'see',
                ],
            ],
            'phpdoc_no_empty_return' => true,
            'phpdoc_no_package' => true,
            'phpdoc_no_useless_inheritdoc' => true,
            'phpdoc_return_self_reference' => true,
            'phpdoc_scalar' => true,
            'phpdoc_separation' => true,
            'phpdoc_single_line_var_spacing' => true,
            'phpdoc_summary' => true,
            'phpdoc_tag_type' => [
                'tags' => [
                    'inheritdoc' => 'inline',
                ],
            ],
            'phpdoc_to_comment' => false,
            'phpdoc_trim' => true,
            'phpdoc_types' => true,
            'phpdoc_types_order' => [
                'null_adjustment' => 'always_last',
                'sort_algorithm' => 'none',
            ],
            'phpdoc_var_without_name' => true,
            'protected_to_private' => true,
            'psr_autoloading' => true,
            'return_type_declaration' => true,
            'self_accessor' => false,
            'semicolon_after_instruction' => true,
            'set_type_to_cast' => true,
            'short_scalar_cast' => true,
            'simplified_null_return' => false,
            'single_blank_line_at_eof' => true,
            'single_class_element_per_statement' => true,
            'single_import_per_statement' => true,
            'single_line_after_imports' => true,
            'single_line_comment_style' => [
                'comment_types' => [
                    'hash',
                ],
            ],
            'single_quote' => true,
            'single_trait_insert_per_statement' => true,
            'space_after_semicolon' => false,
            'spaces_inside_parentheses' => [
                'space' => 'none',
            ],
            'standardize_increment' => true,
            'standardize_not_equals' => true,
            'static_lambda' => true,
            'switch_case_semicolon_to_colon' => true,
            'switch_case_space' => true,
            'ternary_operator_spaces' => true,
            'trailing_comma_in_multiline' => true,
            'trim_array_spaces' => true,
            'type_declaration_spaces' => [
                'elements' => [
                    'function',
                    'property',
                ],
            ],
            'unary_operator_spaces' => true,
            'visibility_required' => true,
            'whitespace_after_comma_in_array' => true,
            'yoda_style' => false,
            'native_type_declaration_casing' => true,
            'no_unneeded_braces' => true,
            'new_with_parentheses' => [
                'named_class' => true,
                'anonymous_class' => true,
            ],
        ];

        $ruleset = new Ibexa50RuleSet();
        self::assertEquals($expectedLocalRules, $ruleset->getRules());
    }

    public function testPhpCsFixerRules(): void
    {
        $expectedPhpCsFixerRules = [
            'concat_space' => [
                'spacing' => 'one',
            ],
            'single_line_empty_body' => true,
            'binary_operator_spaces' => true,
            'blank_lines_before_namespace' => true,
            'class_definition' => [
                'single_item_single_line' => true,
                'inline_constructor_arguments' => false,
            ],
            'declare_equal_normalize' => true,
            'lowercase_cast' => true,
            'lowercase_static_reference' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_leading_import_slash' => true,
            'no_whitespace_in_blank_line' => true,
            'ordered_imports' => true,
            'return_type_declaration' => true,
            'short_scalar_cast' => true,
            'single_import_per_statement' => true,
            'single_trait_insert_per_statement' => true,
            'ternary_operator_spaces' => true,
            'visibility_required' => true,
            'blank_line_after_namespace' => true,
            'constant_case' => [
                'case' => 'lower',
            ],
            'elseif' => true,
            'function_declaration' => true,
            'indentation_type' => true,
            'line_ending' => true,
            'lowercase_keywords' => true,
            'method_argument_space' => true,
            'no_closing_tag' => true,
            'no_spaces_after_function_name' => true,
            'no_trailing_whitespace' => true,
            'no_trailing_whitespace_in_comment' => true,
            'single_blank_line_at_eof' => true,
            'single_class_element_per_statement' => true,
            'single_line_after_imports' => true,
            'spaces_inside_parentheses' => [
                'space' => 'none',
            ],
            'switch_case_semicolon_to_colon' => true,
            'switch_case_space' => true,
            'encoding' => true,
            'full_opening_tag' => true,
            'AdamWojs/phpdoc_force_fqcn_fixer' => true,
            'array_syntax' => [
                'syntax' => 'short',
            ],
            'blank_line_before_statement' => [
                'statements' => [
                    'return',
                ],
            ],
            'class_attributes_separation' => [
                'elements' => [
                    'method' => 'one',
                    'property' => 'one',
                ],
            ],
            'declare_strict_types' => true,
            'dir_constant' => true,
            'ereg_to_preg' => true,
            'error_suppression' => true,
            'fopen_flag_order' => true,
            'function_to_constant' => true,
            'general_phpdoc_tag_rename' => true,
            'header_comment' => [
                'comment_type' => 'PHPDoc',
                'header' => '@copyright Copyright (C) Ibexa AS. All rights reserved.
@license For full copyright and license information view LICENSE file distributed with this source code.',
                'location' => 'after_open',
                'separate' => 'top',
            ],
            'implode_call' => true,
            'include' => true,
            'increment_style' => true,
            'is_null' => true,
            'magic_constant_casing' => true,
            'magic_method_casing' => true,
            'modernize_types_casting' => true,
            'native_constant_invocation' => [
                'fix_built_in' => false,
                'include' => [
                    'DIRECTORY_SEPARATOR',
                    'PHP_SAPI',
                    'PHP_VERSION_ID',
                ],
                'scope' => 'namespaced',
            ],
            'native_function_casing' => true,
            'no_alias_functions' => true,
            'no_blank_lines_after_phpdoc' => true,
            'no_empty_comment' => true,
            'no_empty_phpdoc' => true,
            'no_empty_statement' => true,
            'no_extra_blank_lines' => [
                'tokens' => [
                    'curly_brace_block',
                    'extra',
                    'parenthesis_brace_block',
                    'square_brace_block',
                    'throw',
                    'use',
                ],
            ],
            'no_homoglyph_names' => true,
            'no_leading_namespace_whitespace' => true,
            'no_mixed_echo_print' => true,
            'no_multiline_whitespace_around_double_arrow' => true,
            'no_short_bool_cast' => true,
            'no_singleline_whitespace_before_semicolons' => true,
            'no_spaces_around_offset' => true,
            'no_unneeded_control_parentheses' => true,
            'no_unneeded_final_method' => true,
            'no_unused_imports' => true,
            'no_whitespace_before_comma_in_array' => true,
            'non_printable_character' => true,
            'normalize_index_brace' => true,
            'object_operator_without_whitespace' => true,
            'php_unit_construct' => true,
            'php_unit_fqcn_annotation' => true,
            'phpdoc_indent' => true,
            'phpdoc_inline_tag_normalizer' => true,
            'phpdoc_no_access' => true,
            'phpdoc_no_alias_tag' => [
                'replacements' => [
                    'type' => 'var',
                    'link' => 'see',
                ],
            ],
            'phpdoc_no_empty_return' => true,
            'phpdoc_no_package' => true,
            'phpdoc_no_useless_inheritdoc' => true,
            'phpdoc_return_self_reference' => true,
            'phpdoc_scalar' => true,
            'phpdoc_separation' => true,
            'phpdoc_single_line_var_spacing' => true,
            'phpdoc_summary' => true,
            'phpdoc_tag_type' => [
                'tags' => [
                    'inheritdoc' => 'inline',
                ],
            ],
            'phpdoc_trim' => true,
            'phpdoc_types' => true,
            'phpdoc_types_order' => [
                'null_adjustment' => 'always_last',
                'sort_algorithm' => 'none',
            ],
            'phpdoc_var_without_name' => true,
            'protected_to_private' => true,
            'psr_autoloading' => true,
            'semicolon_after_instruction' => true,
            'set_type_to_cast' => true,
            'single_line_comment_style' => [
                'comment_types' => [
                    'hash',
                ],
            ],
            'single_quote' => true,
            'standardize_increment' => true,
            'standardize_not_equals' => true,
            'static_lambda' => true,
            'trailing_comma_in_multiline' => true,
            'trim_array_spaces' => true,
            'unary_operator_spaces' => true,
            'whitespace_after_comma_in_array' => true,
            'type_declaration_spaces' => [
                'elements' => [
                    'function',
                    'property',
                ],
            ],
            'native_type_declaration_casing' => true,
            'no_trailing_comma_in_singleline' => [
                'elements' => [
                    'arguments',
                    'array_destructuring',
                    'array',
                    'group_import',
                ],
            ],
            'no_unneeded_braces' => true,
            'php_unit_test_case_static_method_calls' => [
                'call_type' => 'self',
            ],
            'array_indentation' => true,
            'new_with_parentheses' => [
                'named_class' => true,
                'anonymous_class' => true,
            ],
        ];

        $ruleset = new Ibexa50RuleSet();
        $expectedRules = new RuleSet($ruleset->getRules());
        self::assertEquals($expectedPhpCsFixerRules, $expectedRules->getRules());
    }
}
