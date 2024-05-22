<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\CodeStyle\PhpCsFixer\Sets;

use PhpCsFixer\ConfigInterface;

interface RuleSetInterface
{
    /**
     * @phpstan-return array<string, array<string, mixed>|bool>
     */
    public function getRules(): array;

    public function buildConfig(): ConfigInterface;
}
