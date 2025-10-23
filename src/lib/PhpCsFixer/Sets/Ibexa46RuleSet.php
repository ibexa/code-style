<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\CodeStyle\PhpCsFixer\Sets;

final class Ibexa46RuleSet extends AbstractIbexaRuleSet
{
    public function getRules(): array
    {
        return array_merge(
            parent::getRules(),
            [
                'spaces_inside_parentheses' => true,
                'single_space_around_construct' => true,
                'control_structure_braces' => true,
                'control_structure_continuation_position' => true,
                'no_multiple_statements_per_line' => true,
                'declare_parentheses' => true,
                'braces_position' => true,
                'statement_indentation' => false,
                'type_declaration_spaces' => true,
                'native_type_declaration_casing' => true,
                'new_with_parentheses' => true,
                'no_trailing_comma_in_singleline' => true,
                'no_unneeded_braces' => true,
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
                'blank_lines_before_namespace' => true,
            ],
        );
    }
}
