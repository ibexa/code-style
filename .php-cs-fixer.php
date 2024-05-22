<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

use Ibexa\CodeStyle\PhpCsFixer\InternalConfigFactory;
use Ibexa\CodeStyle\PhpCsFixer\Sets;

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->files()->name('*.php');

$configFactory = new InternalConfigFactory();
$configFactory->withRuleSet(new Sets\Ibexa50RuleSet());

$config = $configFactory->buildConfig();
$config->setFinder($finder);

return $config;
