<?php

declare(strict_types=1);

namespace WebimpressCodingStandardTest\Sniffs\WhiteSpace;

use WebimpressCodingStandardTest\Sniffs\TestCase;

class NoBlankLineAtStartUnitTest extends TestCase
{
    public function getErrorList(string $testFile = '') : array
    {
        return [
            3 => 1,
            9 => 1,
            17 => 1,
            19 => 1,
            30 => 1,
            36 => 1,
            42 => 1,
            45 => 1,
            47 => 1,
            54 => 1,
        ];
    }

    public function getWarningList(string $testFile = '') : array
    {
        return [];
    }
}
