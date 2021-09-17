<?php
declare(strict_types=1);

namespace HashNumber;

use Code;
use Exception\InputIsTooLarge;
use Exception\UnexpectedCodeLength;

use function strpos;

/**
 * HashNumber
 *
 * Generate reversible codes from numbers and revert them to original number
 *
 * @author Anis Uddin Ahmad <anis.programmer@gmail.com> / Modify Tap0g
 */
class HashNumber extends Code
{

    /**
     * Get a code created from a number
     *
     * @param $input
     * @param string $outputFormat One of Code::FORMAT_* constants. Default Code::FORMAT_ALNUM
     * @param int $minLength
     *
     * @return string
     */
    public function encode(int $input, string $outputFormat = Code::FORMAT_ALNUM, int $minLength = 0): string
    {
        if($minLength > 0) {
            $input += $this->getMinForlength($outputFormat, $minLength);
        }

        $this->throwUnlessAcceptable($input);

        return $this->convertBase((string)$input, self::FORMAT_NUMBER, $outputFormat);
    }

    /**
     * Decode a code to it's original number
     *
     * @param string $input
     * @param string $inputFormat
     * @param int $minLength
     *
     * @return int
     */
    public function decode(string $input, string $inputFormat = Code::FORMAT_ALNUM, int $minLength = 0): int
    {
        $number = (int)$this->convertBase($input, $inputFormat, Code::FORMAT_NUMBER);

        if ($minLength > 0) {
            $number -= $this->getMinForlength($inputFormat, $minLength);
        }

        return (int)$number;
    }
	
	/**
     * @param int $input
     *
     * @return void
     */
    private function throwUnlessAcceptable(int $input): void
    {
        if(false !== strpos((string)$input, 'E+')) {
            throw new InputIsTooLarge("Input is too large to process.");
        }

        if($input < 0) {
            throw new UnexpectedCodeLength("Negative numbers are not acceptable for conversion.");
        }
    }

    /**
     * @param string $outputFormat
     * @param int $minLength
     *
     * @return int
     */
    private function getMinForlength(string $outputFormat, int $minLength):int
    {
        $offset         = str_pad($outputFormat[1], $minLength, $outputFormat[0]);
        $offsetAsNumber = $this->convertBase($offset, $outputFormat, self::FORMAT_NUMBER);
        return (int)$offsetAsNumber;
    }
}
