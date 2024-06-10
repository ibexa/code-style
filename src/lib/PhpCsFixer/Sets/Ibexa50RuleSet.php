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
                'braces' => [
                    'allow_single_line_closure' => true,
                ],
                'type_declaration_spaces' => [
                    'elements' => [
                        'function',
                        'property',
                    ],
                ],
                'native_function_type_declaration_casing' => true,
                'new_with_braces' => true,
                'no_trailing_comma_in_singleline' => [
                    'elements' => [
                        'arguments',
                        'array_destructuring',
                        'array',
                        'group_import',
                    ],
                ],
                'no_unneeded_curly_braces' => true,
                'blank_lines_before_namespace' => true,
                'class_definition' => [
                    'single_item_single_line' => true,
                    'inline_constructor_arguments' => false,
                ],
                'php_unit_test_case_static_method_calls' => [
                    'call_type' => 'self',
                ],
            ],
        );
    }
}
