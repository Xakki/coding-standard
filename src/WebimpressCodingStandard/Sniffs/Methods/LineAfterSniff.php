<?php

declare(strict_types=1);

namespace WebimpressCodingStandard\Sniffs\Methods;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractScopeSniff;
use PHP_CodeSniffer\Util\Tokens;

use function in_array;
use function max;

use const T_ANON_CLASS;
use const T_CLASS;
use const T_CLOSE_CURLY_BRACKET;
use const T_FUNCTION;
use const T_INTERFACE;
use const T_SEMICOLON;
use const T_TRAIT;
use const T_WHITESPACE;

class LineAfterSniff extends AbstractScopeSniff
{
    public function __construct()
    {
        parent::__construct([T_CLASS, T_INTERFACE, T_TRAIT, T_ANON_CLASS], [T_FUNCTION]);
    }

    /**
     * @param int $stackPtr
     * @param int $currScope
     */
    protected function processTokenWithinScope(File $phpcsFile, $stackPtr, $currScope) : void
    {
        $tokens = $phpcsFile->getTokens();

        // Methods with body.
        if (isset($tokens[$stackPtr]['scope_closer'])) {
            $closer = $tokens[$stackPtr]['scope_closer'];
        } else {
            $closer = $phpcsFile->findNext(T_SEMICOLON, $tokens[$stackPtr]['parenthesis_closer'] + 1);
        }

        $lastInLine = $closer;
        while ($tokens[$lastInLine + 1]['line'] === $tokens[$closer]['line']
            && in_array($tokens[$lastInLine + 1]['code'], Tokens::$emptyTokens, true)
        ) {
            ++$lastInLine;
        }
        while ($tokens[$lastInLine]['code'] === T_WHITESPACE) {
            --$lastInLine;
        }

        $contentAfter = $phpcsFile->findNext(T_WHITESPACE, $lastInLine + 1, null, true);
        if ($contentAfter !== false
            && $tokens[$contentAfter]['line'] - $tokens[$closer]['line'] !== 2
            && $tokens[$contentAfter]['code'] !== T_CLOSE_CURLY_BRACKET
        ) {
            $error = 'Expected 1 blank line after method; %d found';
            $found = max($tokens[$contentAfter]['line'] - $tokens[$closer]['line'] - 1, 0);
            $data = [$found];
            $fix = $phpcsFile->addFixableError($error, $closer, 'BlankLinesAfter', $data);

            if ($fix) {
                if ($found) {
                    $phpcsFile->fixer->beginChangeset();
                    for ($i = $closer + 1; $i < $contentAfter - 1; $i++) {
                        $phpcsFile->fixer->replaceToken($i, '');
                    }
                    $phpcsFile->fixer->endChangeset();
                } else {
                    $phpcsFile->fixer->addNewline($lastInLine);
                }
            }
        }
    }

    /**
     * @codeCoverageIgnore
     *
     * @param int $stackPtr
     */
    protected function processTokenOutsideScope(File $phpcsFile, $stackPtr) : void
    {
        // we process only function inside class/interface/trait
    }
}
