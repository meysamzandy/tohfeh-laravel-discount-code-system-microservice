<?php


namespace App\Http\Helper;


class SmallHelper
{

    /**
     * @param $code
     * @return string
     */
    public function changeCodeToUppercase($code): string
    {
        return strtoupper($code);
    }

    /**
     * @param $prefix
     * @param $stringType
     * @param $length
     * @return string|null
     */
    public function codeGenerator($prefix, $stringType, $length): ?string
    {
        $randomString = '';
        $charactersLength = strlen($stringType);
        $prefixToUpper = strtoupper($prefix);
        try {
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $stringType[random_int(0, $charactersLength - 1)];
            }
            $result = $prefixToUpper . $randomString;
        } catch (\Exception $e) {
            $result = null;
        }
        return $result;
    }

    /**
     * @param $inputDate
     * @param $dateBegin
     * @param $dateEnd
     * @return bool
     */
    public function checkDateInterval($inputDate, $dateBegin, $dateEnd): bool
    {
        $paymentDate = date('Y-m-d H:i:s', strtotime($inputDate));
        $contractDateBegin = date('Y-m-d H:i:s', strtotime($dateBegin));
        $contractDateEnd = date('Y-m-d H:i:s', strtotime($dateEnd));
        return ($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd);
    }

}