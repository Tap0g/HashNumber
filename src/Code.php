<?php
declare(strict_types=1);

namespace HashNumber;

use function str_split;
use function strlen;
use function bcadd;
use function bcmul;
use function intval;
use function array_search;
use function bcpow;
use function bcmod;
use function bcdiv;

abstract class Code
{
    const FORMAT_NUMBER        = '0123456789';
    const FORMAT_ALNUM         = 'ParqUksdOvYXtNzhAwxmQfoERVgCiTuJ3eDG9S68b1ZnKyF2L4MIpl7H5jB0cW';
    const FORMAT_ALNUM_SMALL   = '0123456789abcdefghijklmnopqrstuvwxyz';
    const FORMAT_ALNUM_CAPITAL = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const FORMAT_CHAR_SMALL    = 'abcdefghijklmnopqrstwxyz';
    const FORMAT_CHAR_CAPITAL  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @param $numberInput
     * @param $fromBaseInput
     * @param $toBaseInput
     *
     * @return string
     */
    protected function convertBase(string $numberInput, string $fromBaseInput, string $toBaseInput): string
    {
        if ($fromBaseInput == $toBaseInput) {
            return $numberInput;
        }

        $fromBase  = str_split($fromBaseInput, 1);
        $toBase    = str_split($toBaseInput, 1);
        $number    = str_split($numberInput, 1);
        $fromLen   = strlen($fromBaseInput);
        $toLen     = strlen($toBaseInput);
        $numberLen = strlen($numberInput);
        $retval    = '';

        if ($toBaseInput == self::FORMAT_NUMBER) {
            $retval = 0;
            for ($i = 1; $i <= $numberLen; $i++) {
                $retval = bcadd((string)$retval, bcmul((string)intval(array_search($number[$i - 1], $fromBase)), bcpow((string)$fromLen, (string)($numberLen - $i))));
            }
            return (string)$retval;
        }
        if ($fromBaseInput != self::FORMAT_NUMBER) {
            $base10 = $this->convertBase($numberInput, $fromBaseInput, self::FORMAT_NUMBER);
        } else {
            $base10 = $numberInput;
        }
        if ($base10 < strlen($toBaseInput)) {
            return $toBase[(int)$base10];
        }
        while ($base10 != '0') {
            $bcmod = (int)bcmod((string)intval($base10), (string)intval($toLen));
            $retval = $toBase[$bcmod] . $retval;
            
            $base10 = bcdiv((string)intval($base10), (string)$toLen, 0);
        }
        return $retval;
    }
}
