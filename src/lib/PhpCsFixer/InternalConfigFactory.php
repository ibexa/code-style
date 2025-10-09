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
        if (isset($this->ruleSet)) {
            return $this->ruleSet;
        }

        return $this->ruleSet = $this->createRuleSetFromPackage(InstalledVersions::getRootPackage());
    }

    /**
     * @param array{name: string, version: string, pretty_version?: string} $package
     */
    private function createRuleSetFromPackage(array $package): RuleSetInterface
    {
        if (!str_starts_with($package['name'], 'ibexa/')) {
            return new Sets\Ibexa46RuleSet();
        }

        $version = $package['pretty_version'] ?? $package['version'];
        if (str_starts_with($version, 'dev-') || $version === '*') {
            return new Sets\Ibexa50RuleSet();
        }

        // Remove any suffix like -dev, -alpha, etc.
        $version = preg_replace('/-.*$/', '', $version);

        if (version_compare($version, '5.0.0', '>=')) {
            return new Sets\Ibexa50RuleSet();
        }

        return new Sets\Ibexa46RuleSet();
    }

    public function buildConfig(): ConfigInterface
    {
        $config = $this->getRuleSet()->buildConfig();
        $config->setRules(array_merge(
            $config->getRules(),
            $this->customRules,
        ));

        return $config;
    }

    public static function build(): ConfigInterface
    {
        return (new self())->buildConfig();
    }
}
