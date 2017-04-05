<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2017 Martin Aarhof <martin.aarhof@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

declare(strict_types=1);

namespace Lsv\TimestringParser;

/**
 * Class TimestringParser.
 */
class TimestringParser
{
    /**
     * Letters that define minutes.
     *
     * @var array
     */
    protected $minuteLetters;

    /**
     * Letters that define hours.
     *
     * @var array
     */
    protected $hourLetters;

    /**
     * Set this to a number, and if this value is 5,
     * then everything you write equals or lower than this, will become hours
     *
     * @var int
     */
    public $lowMinutes;

    public function __construct(array $hourLetters = ['h'], array $minuteLetters = ['m'], int $lowMinutes = null)
    {
        $this->hourLetters = $hourLetters;
        $this->minuteLetters = $minuteLetters;
        $this->lowMinutes = $lowMinutes;
    }

    /**
     * Parse time string.
     *
     * @param mixed $string The string or integer to parse
     *
     * @return int Minutes as integer
     */
    public function parseTimeString($string) : int
    {
        return $this->parseTime(str_replace(' ', '', $string));
    }

    /**
     * Do the parsing of the string.
     *
     * @param string $time
     *
     * @return int
     */
    private function parseTime(string $time) : int
    {
        if (strpos($time, ':') !== false) {
            list($hour, $min) = explode(':', $time);

            return (int) (trim($hour) * 60) + trim($min);
        }

        if ($this->hasHours($time) || $this->hasMinutes($time)) {
            $minutes = 0;
            $regex = sprintf('/([0-9]+[%s]?)/', implode('|', array_merge($this->hourLetters, $this->minuteLetters)));
            $splits = preg_split($regex, $time, -1, PREG_SPLIT_DELIM_CAPTURE);
            foreach ($splits as $split) {
                if ($split === '') {
                    continue;
                }

                if ($this->hasHours($split)) {
                    $minutes += self::getIntFromString($split, $this->hourLetters) * 60;
                    continue;
                }

                if ($this->hasMinutes($split)) {
                    $minutes += self::getIntFromString($split, $this->minuteLetters);
                    continue;
                }

                $minutes += (int) $split;
            }

            return $minutes;
        }

        if ($this->lowMinutes !== null) {
            if (((int)$time) <= $this->lowMinutes) {
                return ((int)$time) * 60;
            }
        }

        return (int) $time;
    }

    /**
     * Does the string has any minutes letters.
     *
     * @param string $time
     *
     * @return bool
     */
    private function hasMinutes(string $time) : bool
    {
        return self::stringHasLetters($time, $this->minuteLetters);
    }

    /**
     * Does the string has any hours letters.
     *
     * @param string $time
     *
     * @return bool
     */
    private function hasHours(string $time) : bool
    {
        return self::stringHasLetters($time, $this->hourLetters);
    }

    /**
     * Gets a string as integer.
     *
     * @param string $string
     * @param array  $letters
     *
     * @return int
     */
    private static function getIntFromString(string $string, array $letters) : int
    {
        return (int) preg_replace(sprintf('/[%s]/', implode('|', $letters)), '', $string);
    }

    /**
     * Does the string has any of the defined letters.
     *
     * @param string $time
     * @param array  $letters
     *
     * @return bool
     */
    private static function stringHasLetters(string $time, array $letters) : bool
    {
        foreach ($letters as $letter) {
            if (strpos($time, $letter) !== false) {
                return true;
            }
        }

        return false;
    }
}
