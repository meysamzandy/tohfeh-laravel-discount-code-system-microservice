<?php


namespace App\Http\Helper;


use Exception;
use Illuminate\Http\Request;

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
        } catch (Exception $e) {
            $result = null;
        }
        return $result;
    }

    /**
     * @param $input
     * @param $dateBegin
     * @param $dateEnd
     * @return bool
     */
    public function checkDateInterval($input, $dateBegin, $dateEnd): bool
    {
        $inputDate = date('Y-m-d H:i:s', strtotime($input));
        $contractDateBegin = date('Y-m-d H:i:s', strtotime($dateBegin));
        $contractDateEnd = date('Y-m-d H:i:s', strtotime($dateEnd));
        return ($inputDate >= $contractDateBegin) && ($inputDate <= $contractDateEnd);
    }

    /**
     * @param array $features
     * @return bool
     */
    public function checkIfFeatureCouldInsert(array $features): bool
    {
        $count = count($features) - 1;
        $checkinArray = $features;
        for ($i = 0; $i < $count; $i++) {
            foreach ($checkinArray as $key => $value) {
                if ($i === $key) {
                    continue;
                }

                $IntervalStart_timeStatus = $this->checkDateInterval($features[$i]['start_time'], $value['start_time'], $value['end_time']);
                $IntervalEnd_timeStatus = $this->checkDateInterval($features[$i]['end_time'], $value['start_time'], $value['end_time']);
                if (($features[$i]['plan_id'] === $value['plan_id']) && $IntervalStart_timeStatus) {
                    return false;
                }
                if (($features[$i]['plan_id'] === $value['plan_id']) && $IntervalEnd_timeStatus) {
                    return false;
                }

            }
        }
        return true;
    }

}