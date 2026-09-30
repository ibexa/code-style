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
use PhpCsFixer\ParallelAwareConfigInterface;
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
     *
     * @param array{name: string, version: string, pretty_version?: string} $package
     * @param class-string $expectedRuleSetClass
     *
     * @throws \ReflectionException
     */
    public function testVersionBasedRuleSetSelection(
        array $package,
        string $expectedRuleSetClass
    ): void {
        $ruleSet = $this->createRuleSetFromPackage->invoke($this->factory, $package);

        self::assertInstanceOf($expectedRuleSetClass, $ruleSet);
    }

    /**
     * @return array<string, array{0: array{name: string, version: string, pretty_version?: string}, 1: class-string}>
     */
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
            'ibexa_package_4_6_branch' => [
                ['name' => 'ibexa/core', 'version' => '4.6.9999999.9999999-dev', 'pretty_version' => '4.6.x-dev'],
                Ibexa46RuleSet::class,
            ],
            'ibexa_package_5_0_branch' => [
                ['name' => 'ibexa/core', 'version' => '5.0.9999999.9999999-dev', 'pretty_version' => '5.0.x-dev'],
                Ibexa50RuleSet::class,
            ],
            'ibexa_package_detached_head' => [
                ['name' => 'ibexa/core', 'version' => 'dev-52e54b6f2a96e442ef5938e822ee900ce1800466'],
                Ibexa50RuleSet::class,
            ],
            'ibexa_package_dev_main_with_alias' => [
                ['name' => 'ibexa/core', 'version' => 'dev-main', 'aliases' => ['4.6.x-dev']],
                Ibexa46RuleSet::class,
            ],
        ];
    }

    /**
     * @dataProvider provideBranchAliasTestCases
     *
     * @param array{name: string, version: string, pretty_version?: string} $package
     * @param string[] $branchAliases
     * @param class-string $expectedRuleSetClass
     *
     * @throws \ReflectionException
     */
    public function testBranchAliasTakesPrecedence(
        array $package,
        array $branchAliases,
        string $expectedRuleSetClass
    ): void {
        $ruleSet = $this->createRuleSetFromPackage->invoke($this->factory, $package, $branchAliases);

        self::assertInstanceOf($expectedRuleSetClass, $ruleSet);
    }

    /**
     * @return array<string, array{0: array{name: string, version: string, pretty_version?: string}, 1: string[], 2: class-string}>
     */
    public function provideBranchAliasTestCases(): array
    {
        return [
            'detached_head_on_4_6' => [
                ['name' => 'ibexa/scheduler', 'version' => 'dev-52e54b6f2a96e442ef5938e822ee900ce1800466'],
                ['4.6.x-dev'],
                Ibexa46RuleSet::class,
            ],
            'detached_head_on_5_0' => [
                ['name' => 'ibexa/scheduler', 'version' => 'dev-52e54b6f2a96e442ef5938e822ee900ce1800466'],
                ['5.0.x-dev'],
                Ibexa50RuleSet::class,
            ],
            'misguessed_version_on_4_6' => [
                ['name' => 'ibexa/scheduler', 'version' => '6.0.9999999.9999999-dev', 'pretty_version' => '6.0.x-dev'],
                ['4.6.x-dev'],
                Ibexa46RuleSet::class,
            ],
            'non_ibexa_package_ignores_aliases' => [
                ['name' => 'vendor/package', 'version' => 'dev-main'],
                ['6.0.x-dev'],
                Ibexa46RuleSet::class,
            ],
        ];
    }

    public function testRootBranchAliasesAreReadFromComposerJson(): void
    {
        $installPath = sys_get_temp_dir() . '/ibexa-code-style-' . uniqid('', true);
        mkdir($installPath);
        file_put_contents(
            $installPath . '/composer.json',
            (string)json_encode(['extra' => ['branch-alias' => ['dev-main' => '4.6.x-dev']]]),
        );

        try {
            $getRootBranchAliases = (new ReflectionClass(InternalConfigFactory::class))->getMethod('getRootBranchAliases');
            $getRootBranchAliases->setAccessible(true);

            self::assertSame(['4.6.x-dev'], $getRootBranchAliases->invoke($this->factory, $installPath));
            self::assertSame([], $getRootBranchAliases->invoke($this->factory, $installPath . '/missing'));
            self::assertSame([], $getRootBranchAliases->invoke($this->factory, null));
        } finally {
            unlink($installPath . '/composer.json');
            rmdir($installPath);
        }
    }

    public function testWithRuleSet(): void
    {
        $customRuleSet = new Ibexa46RuleSet();
        $this->factory->withRuleSet($customRuleSet);

        self::assertSame($customRuleSet, $this->factory->getRuleSet());
    }

    public function testRunInParallel(): void
    {
        // Note: sequential test instead of separate test cases on purpose

        // sanity check
        /** @var ParallelAwareConfigInterface $config */
        $config = $this->factory->buildConfig();
        self::assertSame(1, $config->getParallelConfig()->getMaxProcesses());

        $this->factory->runInParallel();
        /** @var ParallelAwareConfigInterface $config */
        $config = $this->factory->buildConfig();
        self::assertGreaterThan(1, $config->getParallelConfig()->getMaxProcesses());

        // reset test
        $this->factory->runInParallel(false);
        /** @var ParallelAwareConfigInterface $config */
        $config = $this->factory->buildConfig();
        self::assertSame(1, $config->getParallelConfig()->getMaxProcesses());
    }

    public function testBuildWithoutRunInParallel(): void
    {
        $config = InternalConfigFactory::build();
        self::assertInstanceOf(ParallelAwareConfigInterface::class, $config);
        self::assertSame(1, $config->getParallelConfig()->getMaxProcesses());
    }

    public function testBuildWithRunInParallel(): void
    {
        $config = InternalConfigFactory::build(true);
        self::assertInstanceOf(ParallelAwareConfigInterface::class, $config);
        self::assertGreaterThan(1, $config->getParallelConfig()->getMaxProcesses());
    }
}
