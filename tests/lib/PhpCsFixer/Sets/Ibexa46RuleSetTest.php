<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\CodeStyle\PhpCsFixer\Sets;

use Ibexa\CodeStyle\PhpCsFixer\Sets\Ibexa46RuleSet;
use PhpCsFixer\RuleSet\RuleSet;
use PHPUnit\Framework\TestCase;

final class Ibexa46RuleSetTest extends TestCase
{
    public function testGetLocalRules(): void
    {
        $expectedLocalRules = require __DIR__ . '/expected_rules/4_6_rule_set/local_rules.php';

        $ruleset = new Ibexa46RuleSet();
        self::assertEquals($expectedLocalRules, $ruleset->getRules());
    }

    public function testPhpCsFixerRules(): void
    {
        $ruleset = new Ibexa46RuleSet();
        $phpCsFixerSet = new RuleSet($ruleset->getRules());
        $expectedRules = require __DIR__ . '/expected_rules/4_6_rule_set/php_cs_fixer_rules.php';

        self::assertEquals($expectedRules, $phpCsFixerSet->getRules());
    }
}
