<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\CodeStyle\PhpCsFixer\Rule;

use Ibexa\CodeStyle\PhpCsFixer\Sets\Ibexa50RuleSet;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

final class OrderedImportsFixerTest extends TestCase
{
    private OrderedImportsFixer $fixer;

    protected function setUp(): void
    {
        $this->fixer = new OrderedImportsFixer();

        $orderedImportsRule = (new Ibexa50RuleSet())->getRules()['ordered_imports'];
        self::assertIsArray($orderedImportsRule);
        $this->fixer->configure($orderedImportsRule);
    }

    /**
     * @dataProvider provideFixCases
     */
    public function testFixDoesNotSplitImportTypesIntoSeparateGroups(
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
        yield 'function import stays in the same block as class imports' => [
            <<<'PHP'
                <?php
                use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
                use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;
                use Symfony\Component\DependencyInjection\Reference;
                PHP,
            <<<'PHP'
                <?php
                use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
                use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;
                use Symfony\Component\DependencyInjection\Reference;
                PHP,
        ];

        yield 'const import stays in the same block as class imports' => [
            <<<'PHP'
                <?php
                use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
                use const Symfony\Component\DependencyInjection\Loader\Configurator\SOME_CONST;
                use Symfony\Component\DependencyInjection\Reference;
                PHP,
            <<<'PHP'
                <?php
                use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
                use const Symfony\Component\DependencyInjection\Loader\Configurator\SOME_CONST;
                use Symfony\Component\DependencyInjection\Reference;
                PHP,
        ];

        yield 'mixed import types are sorted alphabetically without grouping' => [
            <<<'PHP'
                <?php
                use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;
                use Symfony\Component\DependencyInjection\Reference;
                use const Symfony\Component\DependencyInjection\Loader\Configurator\SOME_CONST;
                use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
                PHP,
            <<<'PHP'
                <?php
                use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
                use const Symfony\Component\DependencyInjection\Loader\Configurator\SOME_CONST;
                use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;
                use Symfony\Component\DependencyInjection\Reference;
                PHP,
        ];
    }
}
