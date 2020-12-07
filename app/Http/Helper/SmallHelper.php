<?php


namespace App\Http\Helper;

use Exception;
use Illuminate\Http\Request;
use Nowakowskir\JWT\Base64Url;

class SmallHelper
{
    public const RESULT_STATUS = 'resultStats';
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
        ];
        $featuresData = $data['features'];
        $CodeData = [
            'created_type' => $data['created_type'],
            'code' => static::changeCodeToUppercase($data['code']),
            'access_type' => $data['access_type'],
            'usage_limit' => $data['usage_limit'],
            'usage_limit_per_user' => $data['usage_limit_per_user'],
            'first_buy' => $data['first_buy'],
            'has_market' => $data['has_market'],
        ];
        $userListData = $data['uuid_list'] ?? null;
        $marketData = $data['market'] ?? null;
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
            'series' => $data['series'] ?? null,
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
        $marketData = $data['market'] ?? null;
        return array($groupData, $featuresData, $CodeData, $marketData);
    }

    /**
     * @param array $data
     * @return array
     */
    public static function prepareDataForMassiveCodes(array $data): array
    {
        $groupData = [
            'group_name' => $data['group_name'],
            'series' => $data['series'],
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
        $userListData = $data['uuid_list'] ?? null;
        $marketData = $data['market'] ?? null;
        return array($groupData, $featuresData, $CodeData,$userListData, $marketData);
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
            self::RESULT_STATUS => $resultStatus,
            self::STATUS_CODE => $statusCode,
            self::BODY => $body,
            self::MESSAGE => $message
        ];
    }


    /**
     * @param $name
     * @param $version
     * @return array
     */
    public static function prepareMarket($name, $version): array
    {
        $market['market_name'] = $name;
        $versionPisces = explode(".", $version);
        $market['version_major'] = (int)$versionPisces[0];
        $market['version_minor'] = (int)$versionPisces[1];
        $market['version_patch'] = (int)$versionPisces[2];

        return $market;
    }


    /**
     * @param Request $request
     * @return array
     */
    public static function paginationParams(Request $request): array
    {
        $limit = 20;
        $page = (int)$request->input('page') ?: 1;

        if ($request->input('limit')) {
            $limit = $request->input('limit') <= 500 ? (int)$request->input('limit') : 500;
        }
        return array($page, $limit);
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function orderParams(Request $request): array
    {
        $orderColumn = $request->input('orderColumn') ?: 'created_at';
        $orderBy = $request->input('orderBy') ?: 'desc';
        return array($orderColumn, $orderBy);
    }

    /**
     * @param $requestParams
     * @param $query
     * @param Request $request
     * @param $page
     * @param $limit
     * @param $orderColumn
     * @param $orderBy
     * @return array
     */
    public static function fetchList($requestParams, $query, Request $request, $page, $limit, $orderColumn, $orderBy): ?array
    {
        $result = Filter::getData($requestParams, $request, $query, $page, $limit, $orderColumn, $orderBy);
        if (count($result) <= 0) {
            return [
                self::RESULT_STATUS => false,
                self::STATUS_CODE => 200,
                self::BODY => null,
                self::MESSAGE => null,
            ];
        }
        return [
            self::RESULT_STATUS => true,
            self::STATUS_CODE => 200,
            self::BODY => $result,
            self::MESSAGE => null,
        ];

    }

    public static function getPayloadFromJwt($token)
    {
        $token = str_replace('Bearer ', '', $token);
        list($header, $payload, $signature) = explode('.', $token);
        return json_decode(Base64Url::decode($payload), true);
    }
}
