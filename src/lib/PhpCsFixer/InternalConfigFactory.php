<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\CodeStyle\PhpCsFixer;

use Composer\InstalledVersions;
use Ibexa\CodeStyle\PhpCsFixer\Sets\RuleSetInterface;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\ParallelAwareConfigInterface;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

/**
 * Factory for Config instance that should be used for all internal Ibexa packages.
 *
 * @internal
 */
final class InternalConfigFactory
{
    /** @var array<string, mixed> */
    private array $customRules = [];

    private RuleSetInterface $ruleSet;

    private bool $runInParallel = false;

    /**
     * @param array<string, mixed> $rules
     */
    public function withRules(array $rules): self
    {
        $this->customRules = $rules;

        return $this;
    }

    public function withRuleSet(RuleSetInterface $ruleSet): self
    {
        $this->ruleSet = $ruleSet;

        return $this;
    }

    public function getRuleSet(): RuleSetInterface
    {
        if (!isset($this->ruleSet)) {
            $rootPackage = InstalledVersions::getRootPackage();

            $this->ruleSet = $this->createRuleSetFromPackage(
                $rootPackage,
                $this->getRootBranchAliases($rootPackage['install_path'] ?? null),
            );
        }

        return $this->ruleSet;
    }

    public function runInParallel(bool $runInParallel = true): self
    {
        $this->runInParallel = $runInParallel;

        return $this;
    }

    /**
     * Branch aliases are checked first, as the guessed root version is unreliable for a
     * detached checkout (CI builds a PR from a merge commit, which Composer sees as `dev-<sha>`)
     * or for a feature branch equally distant from several release branches.
     *
     * @param array{name: string, version: string, pretty_version?: string, aliases?: string[]} $package
     * @param string[] $branchAliases
     */
    private function createRuleSetFromPackage(
        array $package,
        array $branchAliases = []
    ): RuleSetInterface {
        if (!str_starts_with($package['name'], 'ibexa/')) {
            return new Sets\Ibexa46RuleSet();
        }

        $candidates = array_merge(
            $branchAliases,
            $package['aliases'] ?? [],
            [$package['pretty_version'] ?? $package['version']],
        );

        foreach ($candidates as $candidate) {
            // Matches "4.6.0", "5.0.0-alpha1", "5.0.x-dev" and "dev-4.6" alike
            if (preg_match('/^(?:dev-)?v?(\d+)\.(\d+)/', $candidate, $matches) === 1) {
                return version_compare($matches[1] . '.' . $matches[2], '5.0', '>=')
                    ? new Sets\Ibexa50RuleSet()
                    : new Sets\Ibexa46RuleSet();
            }
        }

        // No numeric version to go by (e.g. "dev-main", "*") - assume the newest rule set
        return new Sets\Ibexa50RuleSet();
    }

    /**
     * @return string[]
     */
    private function getRootBranchAliases(?string $installPath): array
    {
        if ($installPath === null || !is_file($installPath . '/composer.json')) {
            return [];
        }

        $composerJson = json_decode((string)file_get_contents($installPath . '/composer.json'), true);
        $branchAliases = $composerJson['extra']['branch-alias'] ?? [];

        return is_array($branchAliases) ? array_values(array_filter($branchAliases, 'is_string')) : [];
    }

    public function buildConfig(): ConfigInterface
    {
        $config = $this->getRuleSet()->buildConfig();
        $config->setRules(array_merge(
            $config->getRules(),
            $this->customRules,
        ));

        if ($this->runInParallel && $config instanceof ParallelAwareConfigInterface) {
            $config->setParallelConfig(ParallelConfigFactory::detect());
        }

        return $config;
    }

    public static function build(bool $runInParallel = false): ConfigInterface
    {
        return (new self())->runInParallel($runInParallel)->buildConfig();
    }
}
