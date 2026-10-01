<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\CodeStyle\PhpCsFixer\Sets;

/**
 * 6.0 packages are aligned to PHPUnit 11, which made mock expectation methods (`once()`, `never()`, etc.) non-static.
 */
final class Ibexa60RuleSet extends AbstractIbexaRuleSet
{
    public function getRules(): array
    {
        return array_merge(
            (new Ibexa50RuleSet())->getRules(),
            [
                'php_unit_test_case_static_method_calls' => [
                    'call_type' => 'self',
                    'target' => '11.0',
                ],
            ],
        );
    }
}
