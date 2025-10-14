<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\Tests\CodeStyle\PhpCsFixer;

use Ibexa\CodeStyle\PhpCsFixer\InternalConfigFactory;
use Ibexa\CodeStyle\PhpCsFixer\Sets\Ibexa46RuleSet;
use Ibexa\CodeStyle\PhpCsFixer\Sets\Ibexa50RuleSet;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

/**
 * @covers \Ibexa\CodeStyle\PhpCsFixer\InternalConfigFactory
 */
final class InternalConfigFactoryTest extends TestCase
{
    private InternalConfigFactory $factory;

    private ReflectionMethod $createRuleSetFromPackage;

    protected function setUp(): void
    {
        $this->factory = new InternalConfigFactory();
        $reflection = new ReflectionClass(InternalConfigFactory::class);

        // This method is private because we don't want it to be part of the public API.
        // We can't test getRuleSet since it internally uses InstalledVersions::getRootPackage(), which cannot be mocked.
        $this->createRuleSetFromPackage = $reflection->getMethod('createRuleSetFromPackage');
        $this->createRuleSetFromPackage->setAccessible(true);
    }

    /**
     * @dataProvider provideRuleSetTestCases
     */
    public function testVersionBasedRuleSetSelection(array $package, string $expectedRuleSetClass): void
    {
        $ruleSet = $this->createRuleSetFromPackage->invoke($this->factory, $package);

        self::assertInstanceOf($expectedRuleSetClass, $ruleSet);
    }

    public function provideRuleSetTestCases(): array
    {
        return [
            'non_ibexa_package' => [
                ['name' => 'vendor/package', 'version' => '1.0.0'],
                Ibexa46RuleSet::class,
            ],
            'ibexa_package_4_6' => [
                ['name' => 'ibexa/core', 'version' => '4.6.0'],
                Ibexa46RuleSet::class,
            ],
            'ibexa_package_5_0' => [
                ['name' => 'ibexa/core', 'version' => '5.0.0'],
                Ibexa50RuleSet::class,
            ],
            'ibexa_package_5_1' => [
                ['name' => 'ibexa/core', 'version' => '5.1.0'],
                Ibexa50RuleSet::class,
            ],
            'ibexa_package_dev_master' => [
                ['name' => 'ibexa/core', 'version' => 'dev-master'],
                Ibexa50RuleSet::class,
            ],
            'ibexa_package_with_pretty_version' => [
                ['name' => 'ibexa/core', 'version' => '5.0.0', 'pretty_version' => '5.0.0-alpha1'],
                Ibexa50RuleSet::class,
            ],
            'ibexa_package_wildcard' => [
                ['name' => 'ibexa/core', 'version' => '*'],
                Ibexa50RuleSet::class,
            ],
            'ibexa_package_4_6_with_suffix' => [
                ['name' => 'ibexa/core', 'version' => '4.6.0-beta1'],
                Ibexa46RuleSet::class,
            ],
        ];
    }

    public function testWithRuleSet(): void
    {
        $customRuleSet = new Ibexa46RuleSet();
        $this->factory->withRuleSet($customRuleSet);

        self::assertSame($customRuleSet, $this->factory->getRuleSet());
    }
}
