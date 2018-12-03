<?php

declare(strict_types=1);

namespace WebimpressCodingStandard\Sniffs\Commenting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractVariableSniff;

use function array_filter;
use function array_merge;
use function current;
use function key;
use function next;
use function strpos;
use function strtolower;
use function substr;

use const T_COMMENT;
use const T_DOC_COMMENT_CLOSE_TAG;
use const T_DOC_COMMENT_STAR;
use const T_DOC_COMMENT_STRING;
use const T_DOC_COMMENT_WHITESPACE;
use const T_PRIVATE;
use const T_PROTECTED;
use const T_PUBLIC;
use const T_STATIC;
use const T_VAR;
use const T_WHITESPACE;

class VariableCommentSniff extends AbstractVariableSniff
{
    /**
     * @var string[]
     */
    public $allowedTags = [];

    /**
     * @var string[]
     */
    public $nestedTags = [
        '@var',
    ];

    /**
     * @param int $stackPtr
     */
    protected function processMemberVar(File $phpcsFile, $stackPtr) : void
    {
        $tokens = $phpcsFile->getTokens();
        $ignore = [
            T_PUBLIC,
            T_PRIVATE,
            T_PROTECTED,
            T_VAR,
            T_STATIC,
            T_WHITESPACE,
        ];

        $commentEnd = $phpcsFile->findPrevious($ignore, $stackPtr - 1, null, true);
        if ($commentEnd === false
            || ($tokens[$commentEnd]['code'] !== T_DOC_COMMENT_CLOSE_TAG
                && $tokens[$commentEnd]['code'] !== T_COMMENT)
        ) {
            $error = 'Missing member variable doc comment';
            $phpcsFile->addError($error, $stackPtr, 'Missing');
            return;
        }

        if ($tokens[$commentEnd]['code'] === T_COMMENT) {
            if ($tokens[$commentEnd]['line'] === $tokens[$stackPtr]['line'] - 1) {
                $error = 'You must use "/**" style comments for a member variable comment';
                $phpcsFile->addError($error, $commentEnd, 'WrongStyle');
            } else {
                $error = 'Missing member variable doc comment';
                $phpcsFile->addError($error, $stackPtr, 'Missing');
            }

            return;
        }

        $commentStart = $tokens[$commentEnd]['comment_opener'];

        $foundVar = null;

        $tags = $tokens[$commentStart]['comment_tags'];
        while ($tag = current($tags)) {
            $key = key($tags);
            if (isset($tags[$key + 1])) {
                $lastFrom = $tags[$key + 1];
            } else {
                $lastFrom = $tokens[$commentStart]['comment_closer'];
            }

            $last = $phpcsFile->findPrevious(
                [T_DOC_COMMENT_STAR, T_DOC_COMMENT_WHITESPACE],
                $lastFrom - 1,
                null,
                true
            );

            $tagName = strtolower($tokens[$tag]['content']);
            $isValidTag = array_filter(array_merge($this->allowedTags, ['@var']), function ($v) use ($tagName) {
                return strtolower($v) === $tagName;
            });

            if (substr($tokens[$last]['content'], -1) === '{') {
                $dep = 1;
                $i = $last;
                $max = $tokens[$commentStart]['comment_closer'];
                while ($dep > 0 && $i < $max) {
                    $i = $phpcsFile->findNext(T_DOC_COMMENT_STRING, $i + 1, $max);

                    if (! $i) {
                        break;
                    }

                    if (strpos($tokens[$i]['content'], '}') === 0) {
                        --$dep;
                    }

                    if (substr($tokens[$i]['content'], -1) === '{') {
                        ++$dep;
                    }
                }

                if ($dep > 0) {
                    $error = 'Tag contains nested description, but cannot find the closing bracket';
                    $phpcsFile->addError($error, $last, 'NotClosed');
                    return;
                }

                while (isset($tags[$key + 1]) && $tags[$key + 1] < $i) {
                    $tagName = strtolower($tokens[$tags[$key + 1]]['content']);
                    if ($isValidTag && ! array_filter($this->nestedTags, function ($v) use ($tagName) {
                        return strtolower($v) === $tagName;
                    })) {
                        $error = 'Tag %s cannot be nested';
                        $data = [
                            $tokens[$tags[$key + 1]]['content'],
                        ];
                        $phpcsFile->addError($error, $tags[$key + 1], 'NestedTag', $data);
                        return;
                    }

                    next($tags);
                    ++$key;
                }
            }

            $tagName = strtolower($tokens[$tag]['content']);
            if ($tagName === '@var') {
                if ($foundVar !== null) {
                    $error = 'Only one @var tag is allowed in a member variable comment';
                    $phpcsFile->addError($error, $tag, 'DuplicateVar');
                } else {
                    $foundVar = $tag;
                }
            } elseif (! $isValidTag && $tagName[1] === $tokens[$tag]['content'][1]) {
                $error = '%s tag is not allowed in member variable comment';
                $data = [$tokens[$tag]['content']];
                $phpcsFile->addError($error, $tag, 'TagNotAllowed', $data);
            }

            next($tags);
        }

        // The @var tag is the only one we require.
        if ($foundVar === null) {
            $error = 'Missing @var tag in member variable comment';
            $phpcsFile->addError($error, $commentEnd, 'MissingVar');
        }
    }

    /**
     * @param int $stackPtr
     */
    protected function processVariable(File $phpcsFile, $stackPtr) : void
    {
        // Sniff process only class member vars.
    }

    /**
     * @param int $stackPtr
     */
    protected function processVariableInString(File $phpcsFile, $stackPtr) : void
    {
        // Sniff process only class member vars.
    }
}
