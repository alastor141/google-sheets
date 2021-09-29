<?php

namespace Alastor141\Google\Api;

/**
 * Class Sheets
 * @package App\Google\Api
 */
class Sheets
{
    /**
     * @var \Google\Service\Sheets
     */
    protected $sheets;

    /**
     * Sheets constructor.
     * @param \Google\Service\Sheets $sheets
     */
    public function __construct(\Google\Service\Sheets $sheets)
    {
        $this->sheets = $sheets;
    }

    /**
     * @return \Google\Client
     */
    public function getClient()
    {
        return $this->sheets->getClient();
    }

    /**
     * @return \Google\Service\Sheets
     */
    public function getSheets()
    {
        return $this->sheets;
    }

    /**
     * @param $pString
     * @return float|int|mixed
     * @throws \Exception
     */
    public function columnIndexFromString($pString)
    {

        static $indexCache = [];

        if (isset($indexCache[$pString])) {
            return $indexCache[$pString];
        }

        static $columnLookup = [
            'A' => 1, 'B' => 2, 'C' => 3, 'D' => 4, 'E' => 5, 'F' => 6, 'G' => 7, 'H' => 8, 'I' => 9, 'J' => 10, 'K' => 11, 'L' => 12, 'M' => 13,
            'N' => 14, 'O' => 15, 'P' => 16, 'Q' => 17, 'R' => 18, 'S' => 19, 'T' => 20, 'U' => 21, 'V' => 22, 'W' => 23, 'X' => 24, 'Y' => 25, 'Z' => 26,
            'a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5, 'f' => 6, 'g' => 7, 'h' => 8, 'i' => 9, 'j' => 10, 'k' => 11, 'l' => 12, 'm' => 13,
            'n' => 14, 'o' => 15, 'p' => 16, 'q' => 17, 'r' => 18, 's' => 19, 't' => 20, 'u' => 21, 'v' => 22, 'w' => 23, 'x' => 24, 'y' => 25, 'z' => 26,
        ];

        if (isset($pString[0])) {
            if (!isset($pString[1])) {
                $indexCache[$pString] = $columnLookup[$pString]  - 1;
                return $indexCache[$pString];
            }elseif (!isset($pString[2])) {
                $indexCache[$pString] = ($columnLookup[$pString[0]] * 26 + $columnLookup[$pString[1]]) - 1;
                return $indexCache[$pString];
            } elseif (!isset($pString[3])) {
                $indexCache[$pString] = ($columnLookup[$pString[0]] * 676 + $columnLookup[$pString[1]] * 26 + $columnLookup[$pString[2]]) - 1;
                return $indexCache[$pString];
            }
        }

        throw new \Exception('Column string index can not be ' . ((isset($pString[0])) ? 'longer than 3 characters' : 'empty'));
    }

    /**
     * @param $columnIndex
     * @return string
     */
    public function stringFromColumnIndex($columnIndex)
    {
        static $indexCache = [];

        if (!isset($indexCache[$columnIndex])) {
            $indexValue = $columnIndex;
            $base26 = null;
            do {
                $characterValue = ($indexValue % 26) ?: 26;
                $indexValue = ($indexValue - $characterValue) / 26;
                $base26 = chr($characterValue + 64) . ($base26 ?: '');
            } while ($indexValue > 0);
            $indexCache[$columnIndex] = $base26;
        }

        return $indexCache[$columnIndex];
    }
}