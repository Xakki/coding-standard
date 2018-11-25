<?php

declare(strict_types=1);

namespace WebimpressCodingStandardTest\Sniffs\Formatting;

use WebimpressCodingStandardTest\Sniffs\AbstractTestCase;

class UnnecessaryParenthesesUnitTest extends AbstractTestCase
{
    public function getErrorList(string $testFile = '') : array
    {
        return [
            7 => 1,
            9 => 1,
            10 => 1,
            11 => 1,
            12 => 2,
            14 => 3,
            31 => 1,
            33 => 2,
            35 => 1,
            41 => 1,
            43 => 1,
            47 => 2,
            52 => 1,
            57 => 3,
            58 => 1,
            59 => 1,
            62 => 3,
            64 => 1,
            65 => 1,
            72 => 1,
            89 => 1,
            94 => 1,
            95 => 1,
            96 => 1,
            107 => 1,
            108 => 1,
            112 => 1,
            114 => 1,
            120 => 1,
            124 => 1,
            129 => 1,
            131 => 1,
            136 => 3,
            137 => 1,
            138 => 1,
            140 => 1,
            146 => 1,
            149 => 1,
            150 => 1,
            151 => 1,
            152 => 1,
            171 => 3,
            172 => 3,
            173 => 1,
            174 => 1,
            175 => 1,
            176 => 1,
            177 => 1,
            178 => 1,
            179 => 1,
            180 => 1,
        ];
    }

    public function getWarningList(string $testFile = '') : array
    {
        return [];
    }
}
