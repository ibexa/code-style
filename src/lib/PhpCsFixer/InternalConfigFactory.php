<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\CodeStyle\PhpCsFixer;

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
        return $this->ruleSet ??= new Sets\Ibexa46RuleSet();
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
