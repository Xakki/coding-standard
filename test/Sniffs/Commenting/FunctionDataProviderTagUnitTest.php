<?php

declare(strict_types=1);

namespace WebimpressCodingStandardTest\Sniffs\Commenting;

use WebimpressCodingStandardTest\Sniffs\TestCase;

class FunctionDataProviderTagUnitTest extends TestCase
{
    public function getErrorList(string $testFile = '') : array
    {
        return [
            9 => 1,
            16 => 1,
            19 => 1,
            25 => 1,
        ];
    }

    public function getWarningList(string $testFile = '') : array
    {
        return [];
    }
}
