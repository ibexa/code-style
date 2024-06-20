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
                'no_spaces_inside_parenthesis' => true,
                'braces' => [
                    'allow_single_line_closure' => true,
                ],
                'function_typehint_space' => true,
                'native_function_type_declaration_casing' => true,
                'new_with_braces' => true,
                'no_trailing_comma_in_list_call' => true,
                'no_trailing_comma_in_singleline_array' => true,
                'no_unneeded_curly_braces' => true,
                'single_blank_line_before_namespace' => true,
            ],
        );
    }
}
