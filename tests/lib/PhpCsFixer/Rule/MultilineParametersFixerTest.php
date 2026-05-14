<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\CodeStyle\PhpCsFixer\Rule;

use Ibexa\CodeStyle\PhpCsFixer\Rule\MultilineParametersFixer;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

final class MultilineParametersFixerTest extends TestCase
{
    private MultilineParametersFixer $fixer;

    protected function setUp(): void
    {
        $this->fixer = new MultilineParametersFixer();
    }

    /**
     * @dataProvider provideFixCases
     */
    public function testFix(
        string $input,
        string $expected
    ): void {
        $tokens = Tokens::fromCode($input);
        $this->fixer->fix(new SplFileInfo(__FILE__), $tokens);

        self::assertSame($expected, $tokens->generateCode());
    }

    /**
     * @return iterable<string, array{0: string, 1: string}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'single parameter should not be modified' => [
            <<<'PHP'
                <?php
                function bar(array $package): void {
                }
                PHP,
            <<<'PHP'
                <?php
                function bar(array $package): void {
                }
                PHP,
        ];

        yield 'single parameter with type hints should not be modified' => [
            <<<'PHP'
                <?php
                function bar(?string $package = null): void {
                }
                PHP,
            <<<'PHP'
                <?php
                function bar(?string $package = null): void {
                }
                PHP,
        ];

        yield 'multiple parameters should be split' => [
            <<<'PHP'
                <?php
                function foo(array $package, string $expectedRuleSetClass): void {
                }
                PHP,
            <<<'PHP'
                <?php
                function foo(
                    array $package,
                    string $expectedRuleSetClass
                ): void {
                }
                PHP,
        ];

        yield 'multiple parameters with type hints should be split' => [
            <<<'PHP'
                <?php
                function test(?string $foo = null, int $bar = 42): string {
                }
                PHP,
            <<<'PHP'
                <?php
                function test(
                    ?string $foo = null,
                    int $bar = 42
                ): string {
                }
                PHP,
        ];

        yield 'constructor with properties should be split' => [
            <<<'PHP'
                <?php
                class Test {
                    public function __construct(string $foo, int $bar) {
                    }
                }
                PHP,
            <<<'PHP'
                <?php
                class Test {
                    public function __construct(
                        string $foo,
                        int $bar
                    ) {
                    }
                }
                PHP,
        ];

        yield 'already multiline should not be modified' => [
            <<<'PHP'
                <?php
                function test(
                    string $foo,
                    int $bar
                ): void {
                }
                PHP,
            <<<'PHP'
                <?php
                function test(
                    string $foo,
                    int $bar
                ): void {
                }
                PHP,
        ];
    }
}
