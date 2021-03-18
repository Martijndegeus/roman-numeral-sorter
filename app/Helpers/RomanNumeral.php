<?php


namespace App\Helpers;


class RomanNumeral
{
    const NUMERALS = [
        'I' => 1,
        'V' => 5,
        'X' => 10,
        'L' => 50,
    ];

    /**
     * Array of all counted numbers as numerals
     *
     * @var array
     */
    private static $numeralCounts = [];

    /**
     * Converts a numeral to an integer
     *
     * @param string $numeralString
     * @return int
     */
    public static function convertNumeralToNumber(string $numeralString): int
    {
        $chars = str_split($numeralString);
        $sizeOfChars = sizeof($chars);
        $number = 0;

        for ($i = 0; $i < $sizeOfChars; $i++) {
            $number = $number + (self::getIntFromChar($chars[$i]) * self::addOrSubtract($numeralString, $i));
        }

        return $number;
    }

    /**
     * Get the integer value for a numeral
     *
     * @param string $char
     * @return int
     */
    private static function getIntFromChar(string $char): int
    {
        return self::NUMERALS[$char];
    }

    /**
     * Determines if the numeral should be added or subtracted
     *
     * @param string $numeralString
     * @param int $index
     * @return int
     */
    private static function addOrSubtract(string $numeralString, int $index): int
    {
        $chars = str_split($numeralString);
        $result = 1;

        if (isset($chars[$index - 1])) {
            if (self::isLessThanAdjacentNumeral($chars, $index, true)) {
                $result = 1;
            } else {
                $result = -1;
            }

            if (isset($chars[$index + 1])) {
                if (self::isLessThanAdjacentNumeral($chars, $index, false)) {
                    $result = 1;
                }
            } else {
                $result = 1;
            }
        }

        return $result;
    }

    /**
     * Compare to adjacent numeral
     *
     * @param array $chars
     * @param int $index
     * @param bool $previous
     * @return bool
     */
    private static function isLessThanAdjacentNumeral(array $chars, int $index, bool $previous): bool
    {
        if ($previous) {
            $result = self::getIntFromChar($chars[$index - 1]) <= self::getIntFromChar($chars[$index]);
        } else {
            $result = self::getIntFromChar($chars[$index + 1]) <= self::getIntFromChar($chars[$index]);
        }

        return $result;
    }

    /**
     * Converts integer to numerals
     *
     * @param int $number
     * @return string
     */
    public static function convertNumberToNumeral(int $number): string
    {
        $string = '';

        while ($number !== 0) {
            $number = self::countNumerals($number);
        }

        self::cleanUpNumerals();

        return self::generateNumeralString($string);
    }

    /**
     * Counts the numerals
     *
     * @param int $number
     * @return int
     */
    private static function countNumerals(int $number): int
    {
        foreach (array_reverse(self::NUMERALS) as $numeral => $int) {
            $numeralCounter = 0;
            self::$numeralCounts[$numeral] = 0;
            while ($number - $int >= 0 && $numeralCounter < 4) {
                self::$numeralCounts[$numeral]++;
                $number = $number - $int;
                $numeralCounter++;
            }
        }
        return $number;
    }

    /**
     * Replaces numerals with too many occurrences to the correct combination of numerals
     */
    private static function cleanUpNumerals(): void
    {
        foreach (self::$numeralCounts as $numeral => $count) {
            if ($count === 4) {
                self::$numeralCounts[self::getNextNumeral($numeral)]++;
                self::$numeralCounts[$numeral] = -1;
            }
        }
    }

    /**
     * Get the next higher numeral
     *
     * @param string $numeral
     * @return string
     */
    private static function getNextNumeral(string $numeral): string
    {
        $numeralsArray = self::getAdjacentNumeral($numeral, 1);

        return array_search(next($numeralsArray), $numeralsArray);
    }

    /**
     * Get one of the adjacent numerals
     *
     * @param string $numeral
     * @param int $nextOrPrevious
     * @return int[]
     */
    private static function getAdjacentNumeral(string $numeral, int $nextOrPrevious): array
    {
        $numeralsArray = self::NUMERALS;
        $currentKey = key($numeralsArray);
        while ($currentKey !== null && $currentKey != $numeral) {
            if ($nextOrPrevious < 0) {
                prev($numeralsArray);
            } else {
                next($numeralsArray);
            }
            $currentKey = key($numeralsArray);
        }

        return $numeralsArray;
    }

    /**
     * Generates the numeral string
     *
     * @param string $string
     * @return string
     */
    private static function generateNumeralString(string $string): string
    {
        foreach (self::$numeralCounts as $numeral => $count) {
            if ($count < 0) {
                $string = substr($string, 0, -1) . $numeral . substr($string, -1);
            } else {
                for ($i = 0; $i < $count; $i++) {
                    $string .= $numeral;
                }
            }
        }
        return $string;
    }
}
