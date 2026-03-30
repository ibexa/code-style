<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\CodeStyle\PhpCsFixer\Rule;

use Ibexa\CodeStyle\PhpCsFixer\Sets\Ibexa50RuleSet;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBetweenImportGroupsFixer;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

final class OrderedImportsFixerTest extends TestCase
{
    /** @var list<FixerInterface> */
    private array $fixers;

    protected function setUp(): void
    {
        $orderedImportsRule = (new Ibexa50RuleSet())->getRules()['ordered_imports'];
        self::assertIsArray($orderedImportsRule);

        $orderedImportsFixer = new OrderedImportsFixer();
        $orderedImportsFixer->configure($orderedImportsRule);

        $this->fixers = [
            $orderedImportsFixer,
            new BlankLineBetweenImportGroupsFixer(),
        ];
    }

    /**
     * @dataProvider provideFixCases
     */
    public function testFixGroupsAndSortsImportsByType(
        string $input,
        string $expected
    ): void {
        $tokens = Tokens::fromCode($input);

        foreach ($this->fixers as $fixer) {
            if (!$fixer->isCandidate($tokens)) {
                continue;
            }

            $fixer->fix(new SplFileInfo(__FILE__), $tokens);
        }

        self::assertSame($expected, $tokens->generateCode());
    }

    /**
     * @return iterable<string, array{0: string, 1: string}>
     */
    public static function provideFixCases(): iterable
    {
        yield 'function import is moved to its own group after class imports' => [
            <<<'PHP'
                <?php
                use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
                use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;
                use Symfony\Component\DependencyInjection\Reference;
                PHP,
            <<<'PHP'
                <?php
                use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
                use Symfony\Component\DependencyInjection\Reference;

                use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;
                PHP,
        ];

        yield 'const import is moved to its own group after function imports' => [
            <<<'PHP'
                <?php
                use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
                use const Symfony\Component\DependencyInjection\Loader\Configurator\SOME_CONST;
                use Symfony\Component\DependencyInjection\Reference;
                PHP,
            <<<'PHP'
                <?php
                use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
                use Symfony\Component\DependencyInjection\Reference;

                use const Symfony\Component\DependencyInjection\Loader\Configurator\SOME_CONST;
                PHP,
        ];

        yield 'mixed import types are sorted alphabetically within separate groups' => [
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
                use Symfony\Component\DependencyInjection\Reference;

                use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

                use const Symfony\Component\DependencyInjection\Loader\Configurator\SOME_CONST;
                PHP,
        ];
    }
}
