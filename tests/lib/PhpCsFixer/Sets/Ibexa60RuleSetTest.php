<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\CodeStyle\PhpCsFixer\Sets;

use Ibexa\CodeStyle\PhpCsFixer\Sets\Ibexa60RuleSet;
use PhpCsFixer\RuleSet\RuleSet;
use PHPUnit\Framework\TestCase;

final class Ibexa60RuleSetTest extends TestCase
{
    public function testGetLocalRules(): void
    {
        $expectedLocalRules = require __DIR__ . '/expected_rules/6_0_rule_set/local_rules.php';

        $ruleset = new Ibexa60RuleSet();
        self::assertEquals($expectedLocalRules, $ruleset->getRules());
    }

    public function testPhpCsFixerRules(): void
    {
        $expectedPhpCsFixerRules = require __DIR__ . '/expected_rules/6_0_rule_set/php_cs_fixer_rules.php';

        $ruleset = new Ibexa60RuleSet();
        $expectedRules = new RuleSet($ruleset->getRules());
        self::assertEquals($expectedPhpCsFixerRules, $expectedRules->getRules());
    }
}
