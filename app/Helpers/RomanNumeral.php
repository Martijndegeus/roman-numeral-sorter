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

    private static $numeralCounts = [];

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

    private static function getIntFromChar(string $char): int
    {
        return self::NUMERALS[$char];
    }

    private static function addOrSubtract(string $numeralString, int $index): int
    {
        $chars = str_split($numeralString);
        $result = 1;

        if (isset($chars[$index - 1])) {
            if (self::getIntFromChar($chars[$index - 1]) <= self::getIntFromChar($chars[$index])) {
                $result = 1;
            } else {
                $result = -1;
            }

            if (isset($chars[$index + 1])) {
                if (self::getIntFromChar($chars[$index + 1]) <= self::getIntFromChar($chars[$index])) {
                    $result = 1;
                }
            } else {
                $result = 1;
            }
        }

        return $result;
    }

    public static function convertNumberToNumeral(int $number): string
    {
        $string = '';

        while ($number !== 0) {
            foreach (array_reverse(self::NUMERALS) as $numeral => $int) {
                $numeralCounter = 0;
                self::$numeralCounts[$numeral] = 0;
                while ($number - $int >= 0 && $numeralCounter < 4) {
                    self::$numeralCounts[$numeral]++;
                    $number = $number - $int;
                    $numeralCounter++;
                }
            }
        }

        self::cleanUpNumerals();

        foreach (self::$numeralCounts as $numeral => $count) {
            if ($count < 0) {
                $string = substr($string, 0, -1)  . $numeral . substr($string, -1);
            } else {
                for ($i = 0; $i < $count; $i++) {
                    $string .= $numeral;
                }
            }
        }

        return $string;
    }

    private static function getNextNumeral(string $numeral): string
    {
        $numeralsArray = self::getAdjacentNumeral($numeral, 1);

        return array_search(next($numeralsArray), $numeralsArray);
    }

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

    private static function cleanUpNumerals(): void
    {
        foreach (self::$numeralCounts as $numeral => $count) {
            if ($count === 4) {
                self::$numeralCounts[self::getNextNumeral($numeral)]++;
                self::$numeralCounts[$numeral] = -1;
            }
        }
    }
}
