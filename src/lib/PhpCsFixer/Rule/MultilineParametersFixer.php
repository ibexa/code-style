<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace Ibexa\CodeStyle\PhpCsFixer\Rule;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

final class MultilineParametersFixer extends AbstractFixer
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Methods and functions with 2+ parameters must use multiline format',
            [
                new CodeSample(
                    '<?php function foo(string $a, int $b): void {}'
                ),
            ]
        );
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(T_FUNCTION);
    }

    protected function applyFix(
        \SplFileInfo $file,
        Tokens $tokens
    ): void {
        for ($index = $tokens->count() - 1; $index >= 0; --$index) {
            if (!$tokens[$index]->isGivenKind(T_FUNCTION)) {
                continue;
            }

            $openParenIndex = $tokens->getNextTokenOfKind($index, ['(']);
            $closeParenIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $openParenIndex);

            // Count commas to determine parameter count
            $paramCount = $this->countParameters($tokens, $openParenIndex, $closeParenIndex);

            // Only process if 2+ parameters
            if ($paramCount < 2) {
                continue;
            }

            // Check if already multiline
            if ($this->isMultiline($tokens, $openParenIndex, $closeParenIndex)) {
                continue;
            }

            // Apply multiline formatting
            $this->makeMultiline($tokens, $openParenIndex, $closeParenIndex);
        }
    }

    private function countParameters(
        Tokens $tokens,
        int $start,
        int $end
    ): int {
        $count = 0;
        $depth = 0;

        for ($i = $start + 1; $i < $end; ++$i) {
            if ($tokens[$i]->equals('(') || $tokens[$i]->equals('[')) {
                ++$depth;
            } elseif ($tokens[$i]->equals(')') || $tokens[$i]->equals(']')) {
                --$depth;
            } elseif ($depth === 0 && $tokens[$i]->equals(',')) {
                ++$count;
            }
        }

        // If we found any commas, param count is commas + 1
        // If no commas but there's content, it's 1 param
        if ($count > 0) {
            return $count + 1;
        }

        // Check if there's any non-whitespace content
        for ($i = $start + 1; $i < $end; ++$i) {
            if (!$tokens[$i]->isWhitespace()) {
                return 1;
            }
        }

        return 0;
    }

    private function isMultiline(
        Tokens $tokens,
        int $start,
        int $end
    ): bool {
        for ($i = $start; $i <= $end; ++$i) {
            if ($tokens[$i]->isGivenKind(T_WHITESPACE) && str_contains($tokens[$i]->getContent(), "\n")) {
                return true;
            }
        }

        return false;
    }

    private function makeMultiline(
        Tokens $tokens,
        int $openParenIndex,
        int $closeParenIndex
    ): void {
        $indent = $this->detectIndent($tokens, $openParenIndex);
        $lineIndent = str_repeat(' ', 4); // 4 spaces for parameters

        // Add newline after opening parenthesis
        $tokens->insertAt($openParenIndex + 1, new Token([T_WHITESPACE, "\n" . $indent . $lineIndent]));
        ++$closeParenIndex;

        // Find all commas and add newlines after them
        $depth = 0;
        for ($i = $openParenIndex + 1; $i < $closeParenIndex; ++$i) {
            if ($tokens[$i]->equals('(') || $tokens[$i]->equals('[')) {
                ++$depth;
            } elseif ($tokens[$i]->equals(')') || $tokens[$i]->equals(']')) {
                --$depth;
            } elseif ($depth === 0 && $tokens[$i]->equals(',')) {
                // Remove any whitespace after comma
                $nextIndex = $i + 1;
                while ($nextIndex < $closeParenIndex && $tokens[$nextIndex]->isWhitespace()) {
                    $tokens->clearAt($nextIndex);
                    ++$nextIndex;
                }

                // Insert newline with proper indentation
                $tokens->insertAt($i + 1, new Token([T_WHITESPACE, "\n" . $indent . $lineIndent]));
                $closeParenIndex = $tokens->findBlockEnd(Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $openParenIndex);
            }
        }

        // Add newline before closing parenthesis
        $tokens->insertAt($closeParenIndex, new Token([T_WHITESPACE, "\n" . $indent]));

        // Handle the opening brace
        $nextNonWhitespace = $tokens->getNextNonWhitespace($closeParenIndex);
        if ($nextNonWhitespace !== null && $tokens[$nextNonWhitespace]->equals(T_CURLY_OPEN)) {
            $tokens->ensureWhitespaceAtIndex($nextNonWhitespace - 1, 1, ' ');
        }
    }

    private function detectIndent(
        Tokens $tokens,
        int $index
    ): string {
        // Look backwards to find the indentation of the current line
        for ($i = $index - 1; $i >= 0; --$i) {
            if ($tokens[$i]->isGivenKind(T_WHITESPACE) && str_contains($tokens[$i]->getContent(), "\n")) {
                $lines = explode("\n", $tokens[$i]->getContent());

                return end($lines);
            }
        }

        return '';
    }

    public function getName(): string
    {
        return 'Ibexa/multiline_parameters';
    }
}
