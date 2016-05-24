<?php

namespace Lsv\TimestringParserTest;

use Lsv\TimestringParser\TimestringParser;

class TimestringParserTest extends \PHPUnit_Framework_TestCase
{

    public function dataProvider()
    {
        return [
            ['3:20', (3 * 60) + 20],
            ['4h48', (4 * 60) + 48],
            ['3h 20m', (3 * 60) + 20],
            ['2h20m', (2 * 60) + 20],
            ['1h', 60],
            [20, 20],
            ['20m', 20]
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param string $string
     * @param int $expected
     */
    public function test_time_parser($string, $expected)
    {
        $this->assertEquals($expected, (new TimestringParser)->parseTimeString($string));
    }

    public function dataProviderFunnyLetters()
    {
        return [
            ['1t 20m', 60 + 20, ['t'], ['m']],
            ['4t20m', (4 * 60) + 20, ['t'], ['m']],
            ['1t', 60, ['t'], ['m']],
            ['1b 20a', 60 + 20, ['b'], ['a']],
            ['3x20v', (3 * 60) + 20, ['x'], ['v']],
        ];
    }

    /**
     * @dataProvider dataProviderFunnyLetters
     * @param string $string
     * @param int $expected
     * @param array $hourLetters
     * @param array $minLetters
     */
    public function test_funny_letters($string, $expected, $hourLetters, $minLetters)
    {
        $this->assertEquals($expected, (new TimestringParser($hourLetters, $minLetters))->parseTimeString($string));
    }

    public function dataProviderReadme()
    {
        return [
            ['3:20', (3 * 60) + 20],
            ['1h 42m', (1 * 60) + 42],
            ['1u 22x', (1 * 60) + 22],
            ['3u55x', (3 * 60) + 55],
            [20, 20],
            ['1h', 60],
            ['48h84y', (48 * 60) + 84],
            ['3t994x', (3 * 60) + 994]
        ];
    }

    /**
     * @dataProvider dataProviderReadme
     * @param string $string
     * @param int $expected
     */
    public function test_readme_examples($string, $expected)
    {
        $parser = new TimestringParser(['h','t','u'], ['m','x','y']);
        $this->assertEquals($expected, $parser->parseTimeString($string));
    }

}
