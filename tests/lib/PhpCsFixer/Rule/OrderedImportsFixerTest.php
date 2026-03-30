<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\CodeStyle\PhpCsFixer\Rule;

use Ibexa\CodeStyle\PhpCsFixer\Sets\Ibexa46RuleSet;
use Ibexa\CodeStyle\PhpCsFixer\Sets\Ibexa50RuleSet;
use Ibexa\CodeStyle\PhpCsFixer\Sets\RuleSetInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBetweenImportGroupsFixer;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

final class OrderedImportsFixerTest extends TestCase
{
    /**
     * @dataProvider provideFixCases
     */
    public function testFixGroupsAndSortsImportsByType(
        RuleSetInterface $ruleSet,
        string $input,
        string $expected
    ): void {
        $orderedImportsRule = $ruleSet->getRules()['ordered_imports'];
        self::assertIsArray($orderedImportsRule);

        $orderedImportsFixer = new OrderedImportsFixer();
        $orderedImportsFixer->configure($orderedImportsRule);

        /** @var list<FixerInterface> $fixers */
        $fixers = [
            $orderedImportsFixer,
            new BlankLineBetweenImportGroupsFixer(),
        ];

        $tokens = Tokens::fromCode($input);

        foreach ($fixers as $fixer) {
            if (!$fixer->isCandidate($tokens)) {
                continue;
            }

            $fixer->fix(new SplFileInfo(__FILE__), $tokens);
        }

        self::assertSame($expected, $tokens->generateCode());
    }

    /**
     * @return iterable<string, array{0: RuleSetInterface, 1: string, 2: string}>
     */
    public static function provideFixCases(): iterable
    {
        $ruleSets = [
            '50 ruleset' => new Ibexa50RuleSet(),
            '46 ruleset' => new Ibexa46RuleSet(),
        ];

        foreach ($ruleSets as $ruleSetName => $ruleSet) {
            yield $ruleSetName . ' function import is moved to its own group after class imports' => [
                $ruleSet,
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

            yield $ruleSetName . ' const import is moved to its own group after function imports' => [
                $ruleSet,
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

            yield $ruleSetName . ' mixed import types are sorted alphabetically within separate groups' => [
                $ruleSet,
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
}
