<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\CodeStyle\PhpCsFixer\Rule;

use Ibexa\CodeStyle\PhpCsFixer\Sets\Ibexa46RuleSet;
use Ibexa\CodeStyle\PhpCsFixer\Sets\Ibexa50RuleSet;
use Ibexa\CodeStyle\PhpCsFixer\Sets\Ibexa60RuleSet;
use Ibexa\CodeStyle\PhpCsFixer\Sets\RuleSetInterface;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitTestCaseStaticMethodCallsFixer;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

final class PhpUnitTestCaseStaticMethodCallsFixerTest extends TestCase
{
    /**
     * @dataProvider provideFixCases
     */
    public function testFixTestCaseMethodCalls(
        RuleSetInterface $ruleSet,
        string $input,
        string $expected
    ): void {
        $fixer = new PhpUnitTestCaseStaticMethodCallsFixer();
        // Resolved like PHP CS Fixer does, so a rule enabled by a nested set (e.g. `@PER-CS2x0`) counts too
        $rules = new RuleSet($ruleSet->getRules());
        $tokens = Tokens::fromCode($input);

        if ($rules->hasRule($fixer->getName())) {
            $fixer->configure($rules->getRuleConfiguration($fixer->getName()) ?? []);
            $fixer->fix(new SplFileInfo(__FILE__), $tokens);
        }

        self::assertSame($expected, $tokens->generateCode());
    }

    /**
     * @return iterable<string, array{0: RuleSetInterface, 1: string, 2: string}>
     */
    public static function provideFixCases(): iterable
    {
        yield '60 ruleset makes mock expectations dynamic, keeps assertions static' => [
            new Ibexa60RuleSet(),
            self::buildTestCase('self::once()', 'self::never()', 'self::exactly(2)', 'self::assertSame(1, 1)'),
            self::buildTestCase('$this->once()', '$this->never()', '$this->exactly(2)', 'self::assertSame(1, 1)'),
        ];

        yield '60 ruleset keeps correct PHPUnit 11 calls' => [
            new Ibexa60RuleSet(),
            self::buildTestCase('$this->once()', '$this->never()', '$this->exactly(2)', 'self::assertSame(1, 1)'),
            self::buildTestCase('$this->once()', '$this->never()', '$this->exactly(2)', 'self::assertSame(1, 1)'),
        ];

        yield '50 ruleset makes mock expectations static' => [
            new Ibexa50RuleSet(),
            self::buildTestCase('$this->once()', '$this->never()', '$this->exactly(2)', '$this->assertSame(1, 1)'),
            self::buildTestCase('self::once()', 'self::never()', 'self::exactly(2)', 'self::assertSame(1, 1)'),
        ];

        yield '46 ruleset leaves static calls alone' => [
            new Ibexa46RuleSet(),
            self::buildTestCase('self::once()', 'self::never()', 'self::exactly(2)', 'self::assertSame(1, 1)'),
            self::buildTestCase('self::once()', 'self::never()', 'self::exactly(2)', 'self::assertSame(1, 1)'),
        ];

        yield '46 ruleset leaves dynamic calls alone' => [
            new Ibexa46RuleSet(),
            self::buildTestCase('$this->once()', '$this->never()', '$this->exactly(2)', '$this->assertSame(1, 1)'),
            self::buildTestCase('$this->once()', '$this->never()', '$this->exactly(2)', '$this->assertSame(1, 1)'),
        ];
    }

    private static function buildTestCase(
        string $once,
        string $never,
        string $exactly,
        string $assertion
    ): string {
        return <<<PHP
            <?php
            final class FooTest extends \\PHPUnit\\Framework\\TestCase
            {
                public function testFoo(): void
                {
                    \$this->createMock(Foo::class)->expects({$once})->method('foo');
                    \$this->createMock(Foo::class)->expects({$never})->method('bar');
                    \$this->createMock(Foo::class)->expects({$exactly})->method('baz');
                    {$assertion};
                }
            }
            PHP;
    }
}
