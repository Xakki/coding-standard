<?php

declare(strict_types=1);

namespace WebimpressCodingStandardTest\Sniffs\Files;

use WebimpressCodingStandardTest\Sniffs\TestCase;

class DeclareStrictTypesUnitTest extends TestCase
{
    public function getErrorList(string $testFile = '') : array
    {
        switch ($testFile) {
            case 'DeclareStrictTypesUnitTest.2.inc':
                return [3 => 1];
            case 'DeclareStrictTypesUnitTest.3.inc':
                return [7 => 1];
            case 'DeclareStrictTypesUnitTest.4.inc':
                return [7 => 1];
            case 'DeclareStrictTypesUnitTest.5.inc':
                return [8 => 1];
            case 'DeclareStrictTypesUnitTest.6.inc':
                return [12 => 1];
        }

        return [];
    }

    public function getWarningList(string $testFile = '') : array
    {
        return [];
    }
}
