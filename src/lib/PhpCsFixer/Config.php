<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\CodeStyle\PhpCsFixer;

use PhpCsFixer\Config as ConfigBase;

/**
 * @deprecated 1.3.0 The "Config" class has been deprecated, will be removed in 2.0. Use RuleSetInterface or InternalConfigFactory instead.
 */
class Config extends ConfigBase
{
    public function __construct(string $name = 'default')
    {
        parent::__construct($name);

        $this->setRiskyAllowed(true);
        $ruleSet = new Sets\Ibexa50RuleSet();
        $this->setRules($ruleSet->getRules());
    }
}
