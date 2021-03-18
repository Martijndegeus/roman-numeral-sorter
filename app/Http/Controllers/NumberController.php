<?php


namespace App\Http\Controllers;

use App\Helpers\NamesArrayModifier;

class NumberController extends Controller
{
    const NAMES = [
        'JAMES XVI',
        'ZACH XIX',
        'JAMIE II',
        'JAMES XXII',
        'CURTIS LVI',
        'JAMES XIV',
    ];

    public function getOrderedList(): array
    {
        $array = new NamesArrayModifier(self::NAMES);

        return $array->sort();
    }
}
