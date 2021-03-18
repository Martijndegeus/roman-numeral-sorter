<?php


namespace App\Helpers;


class NamesArrayModifier
{
    private $names;

    /**
     * NamesArrayModifier constructor.
     * @param array $names
     */
    public function __construct(array $names)
    {
        $this->names = $names;

        return $this;
    }

    /**
     * Sorts the given list of names and numerals
     *
     * @return array
     */
    public function sort(): array
    {
        $this->denumeralizeNames();
        $this->orderNames();
        $this->numeralizeNames();

        return $this->names;
    }

    /**
     * Converts the numerals in the names to integers
     */
    private function denumeralizeNames(): void
    {
        $this->convertNames(true);
    }

    /**
     * Converts the integers in the names to numerals
     */
    private function numeralizeNames(): void
    {
        $this->convertNames(false);
    }

    /**
     * Converts between numerals and integers
     *
     * @param bool $denumeralize
     */
    private function convertNames(bool $denumeralize): void
    {
        $names = [];
        $sizeOfNames = sizeof($this->names);

        for ($i = 0; $i < $sizeOfNames; $i++) {
            $nameElements = explode(' ', $this->names[$i]);

            if ($denumeralize) {
                $nameElements[1] = RomanNumeral::convertNumeralToNumber($nameElements[1]);
            } else {
                $nameElements[1] = RomanNumeral::convertNumberToNumeral($nameElements[1]);
            }

            $names[] = implode(' ',$nameElements);
        }

        $this->names = $names;
    }

    /**
     * Orders the list of names - with integers
     */
    private function orderNames()
    {
        usort($this->names, function ($a, $b){
            return $a <=> $b;
        });
    }
}
