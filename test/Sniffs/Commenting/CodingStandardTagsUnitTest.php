<?php

declare(strict_types=1);

namespace WebimpressCodingStandardTest\Sniffs\Commenting;

use WebimpressCodingStandardTest\Sniffs\AbstractTestCase;

class CodingStandardTagsUnitTest extends AbstractTestCase
{
    protected function getErrorList(string $testFile = '') : array
    {
        return [
            3 => 1,
            4 => 1,
            5 => 1,
            6 => 1,
            7 => 1,
            11 => 1,
            15 => 1,
            19 => 1,
            23 => 1,
            27 => 1,
            30 => 1,
            31 => 1,
            32 => 1,
            33 => 1,
            34 => 1,
            36 => 1,
            37 => 1,
            38 => 1,
            39 => 1,
            40 => 1,
        ];
    }

    protected function getWarningList(string $testFile = '') : array
    {
        return [];
    }
}
