<?php


namespace App\Http\Helper;


use Exception;
use Illuminate\Http\Request;

class SmallHelper
{
    public const RESULT_STATS = 'resultStats';
    public const STATUS_CODE = 'statusCode';
    public const BODY = 'body';
    public const MESSAGE = 'message';
    /**
     * @param $code
     * @return string
     */
    public static function changeCodeToUppercase($code): string
    {
        return strtoupper($code);
    }

    /**
     * @param $prefix
     * @param $stringType
     * @param $length
     * @return string|null
     */
    public static function codeGenerator($prefix, $stringType, $length): ?string
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
     * @param array $data
     * @return array
     */
    public static function prepareDataForManualCodes(array $data): array
    {
        $groupData = [
            'group_name' => $data['group_name'],
            'series' => $data['series']
        ];
        $featuresData = $data['features'];
        $CodeData = [
            'created_type' => $data['created_type'],
            'code' =>  static::changeCodeToUppercase($data['code']),
            'access_type' => $data['access_type'],
            'usage_limit' => $data['usage_limit'],
            'usage_limit_per_user' => $data['usage_limit_per_user'],
            'first_buy' => $data['first_buy'],
            'has_market' => $data['has_market'],
        ];
        $userListData = $data['uuid_list'];
        $marketData = $data['market'];
        return array($groupData, $featuresData, $CodeData, $userListData, $marketData);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function prepareDataForAutoCodes(array $data): array
    {
        $groupData = [
            'group_name' => $data['group_name'],
            'series' => $data['series']
        ];
        $featuresData = $data['features'];
        $CodeData = [
            'created_type' => $data['created_type'],
            'access_type' => $data['access_type'],
            'usage_limit' => $data['usage_limit'],
            'usage_limit_per_user' => $data['usage_limit_per_user'],
            'first_buy' => $data['first_buy'],
            'has_market' => $data['has_market'],
        ];
        $marketData = $data['market'];
        return array($groupData, $featuresData, $CodeData, $marketData);
    }

    /**
     * @param bool $resultStatus
     * @param int $statusCode
     * @param null $body
     * @param null $message
     * @return array
     */
    public static function returnStatus(bool $resultStatus, int $statusCode, $body = null, $message = null): array
    {
        return [
            self::RESULT_STATS => $resultStatus,
            self::STATUS_CODE => $statusCode,
            self::BODY => $body,
            self::MESSAGE => $message
        ];
    }


}