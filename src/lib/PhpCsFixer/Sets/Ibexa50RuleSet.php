<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\CodeStyle\PhpCsFixer\Sets;

final class Ibexa50RuleSet extends AbstractIbexaRuleSet
{
    public function getRules(): array
    {
        return array_merge(
            [
                '@PER-CS2.0' => true,
            ],
            parent::getRules(),
            [
                'spaces_inside_parentheses' => [
                    'space' => 'none',
                ],

                // Replaces 'braces_position'
                // 'braces_position' => [
                //     'allow_single_line_anonymous_functions' => true,
                // ],
                'braces' => [
                    'allow_single_line_closure' => true,
                ],

                // 'function_typehint_space' => true
                'type_declaration_spaces' => ['elements' => ['function', 'property']],

                // 'native_type_declaration_casing' => true,
                'native_function_type_declaration_casing' => true,

                // 'new_with_parentheses' => ['named_class' => true, 'anonymous_class' => true],
                'new_with_braces' => true,

                // 'no_trailing_comma_in_list_call' => true,
                // 'no_trailing_comma_in_singleline_array' => true,
                'no_trailing_comma_in_singleline' => ['elements' => ['arguments', 'array_destructuring', 'array', 'group_import']],

                // 'no_unneeded_braces' => true,
                'no_unneeded_curly_braces' => true,

                // 'single_blank_line_before_namespace' => true,
                'blank_lines_before_namespace' => true,

                'class_definition' => [
                    'single_item_single_line' => true,
                    'inline_constructor_arguments' => false,
                ],
                'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
            ],
        );
    }
}
