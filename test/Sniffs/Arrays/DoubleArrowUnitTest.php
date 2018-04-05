<?php

declare(strict_types=1);

namespace WebimpressCodingStandardTest\Sniffs\Arrays;

use WebimpressCodingStandardTest\Sniffs\TestCase;

class DoubleArrowUnitTest extends TestCase
{
    public function getErrorList(string $testFile = '') : array
    {
        return [
            3 => 1,
            5 => 1,
            9 => 1,
        ];
    }

    public function getWarningList(string $testFile = '') : array
    {
        return [];
    }
}
