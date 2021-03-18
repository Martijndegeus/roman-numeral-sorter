<?php


namespace App\Helpers;


class NamesArrayModifier
{
    private $names;

    public function __construct(array $names)
    {
        $this->names = $names;

        return $this;
    }

    public function sort(): array
    {
        $this->denumeralizeNames();
        $this->orderNames();
        $this->numeralizeNames();

        return $this->names;
    }

    private function denumeralizeNames(): void
    {
        $this->convertNames(true);
    }

    private function numeralizeNames(): void
    {
        $this->convertNames(false);
    }

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

    private function orderNames()
    {
        usort($this->names, function ($a, $b){
            return $a <=> $b;
        });
    }
}
