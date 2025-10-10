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
        $this->fixer->fix(new \SplFileInfo(__FILE__), $tokens);

        self::assertSame($expected, $tokens->generateCode());
    }

    public static function provideFixCases(): iterable
    {
        yield 'single parameter should not be modified' => [
            '<?php
function bar(array $package): void {
}',
            '<?php
function bar(array $package): void {
}',
        ];

        yield 'single parameter with type hints should not be modified' => [
            '<?php
function bar(?string $package = null): void {
}',
            '<?php
function bar(?string $package = null): void {
}',
        ];

        yield 'multiple parameters should be split' => [
            '<?php
function foo(array $package, string $expectedRuleSetClass): void {
}',
            '<?php
function foo(
    array $package,
    string $expectedRuleSetClass
): void {
}',
        ];

        yield 'multiple parameters with type hints should be split' => [
            '<?php
function test(?string $foo = null, int $bar = 42): string {
}',
            '<?php
function test(
    ?string $foo = null,
    int $bar = 42
): string {
}',
        ];

        yield 'constructor with properties should be split' => [
            '<?php
class Test {
    public function __construct(string $foo, int $bar) {
    }
}',
            '<?php
class Test {
    public function __construct(
        string $foo,
        int $bar
    ) {
    }
}',
        ];

        yield 'already multiline should not be modified' => [
            '<?php
function test(
    string $foo,
    int $bar
): void {
}',
            '<?php
function test(
    string $foo,
    int $bar
): void {
}',
        ];
    }
}
