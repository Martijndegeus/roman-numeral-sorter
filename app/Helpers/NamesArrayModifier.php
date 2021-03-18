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
        return $this
            ->denumeralizeNames()
            ->orderNames()
            ->numeralizeNames()
            ->getNames();
    }

    /**
     * @return array
     */
    public function getNames(): array
    {
        return $this->names;
    }

    /**
     * Converts the integers in the names to numerals
     */
    private function numeralizeNames(): NamesArrayModifier
    {
        return $this->convertNames(false);
    }

    /**
     * Converts between numerals and integers
     *
     * @param bool $denumeralize
     */
    private function convertNames(bool $denumeralize): NamesArrayModifier
    {
        $sizeOfNames = sizeof($this->names);

        for ($i = 0; $i < $sizeOfNames; $i++) {
            $nameElements = explode(' ', $this->names[$i]);

            if ($denumeralize) {
                $nameElements[1] = RomanNumeral::convertNumeralToNumber($nameElements[1]);
            } else {
                $nameElements[1] = RomanNumeral::convertNumberToNumeral($nameElements[1]);
            }

            $this->names[$i] = implode(' ', $nameElements);
        }

        return $this;
    }

    /**
     * Orders the list of names - with integers
     */
    private function orderNames(): NamesArrayModifier
    {
        usort($this->names, function ($a, $b) {
            return $a <=> $b;
        });

        return $this;
    }

    /**
     * Converts the numerals in the names to integers
     */
    private function denumeralizeNames(): NamesArrayModifier
    {
        return $this->convertNames(true);
    }
}
