<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\CodeStyle\PhpCsFixer;

use AdamWojs\PhpCsFixerPhpdocForceFQCN\Fixer\Phpdoc\ForceFQCNFixer;
use PhpCsFixer\ConfigInterface;

/**
 * Factory for Config instance that should be used for all internal Ibexa packages.
 *
 * @internal
 */
final class InternalConfigFactory
{
    /** @deprecated Use IBEXA_PHP_HEADER constant instead. */
    public const EZPLATFORM_PHP_HEADER = self::IBEXA_PHP_HEADER;

    public const IBEXA_PHP_HEADER = <<<'EOF'
@copyright Copyright (C) Ibexa AS. All rights reserved.
@license For full copyright and license information view LICENSE file distributed with this source code.
EOF;

    /** @var array<string, mixed> */
    private $customRules = [];

    /**
     * @param array<string, mixed> $rules
     */
    public function withRules(array $rules): self
    {
        $this->customRules = $rules;

        return $this;
    }

    public function buildConfig(): ConfigInterface
    {
        $config = new Config();
        $config->registerCustomFixers([
            new ForceFQCNFixer(),
        ]);

        $specificRules = [
            'header_comment' => [
                'comment_type' => 'PHPDoc',
                'header' => static::IBEXA_PHP_HEADER,
                'location' => 'after_open',
                'separate' => 'top',
            ],
            'AdamWojs/phpdoc_force_fqcn_fixer' => true,
        ];
        $config->setRules(array_merge(
            $config->getRules(),
            $specificRules,
            $this->customRules
        ));

        return $config;
    }

    public static function build(): ConfigInterface
    {
        $self = new self();

        return $self->buildConfig();
    }
}
