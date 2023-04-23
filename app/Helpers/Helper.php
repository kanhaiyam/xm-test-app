<?php

namespace App\Helpers;

use App\Models\Symbol;
use Carbon\Carbon;

class Helper
{
    public static function getHistoricalData(array $payload): array
    {
        $url = 'https://yh-finance.p.rapidapi.com/stock/v3/get-historical-data'.self::buildHttpQueryString($payload);
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 0,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'X-RapidAPI-Key:'.config('app.api_key'),
                'X-RapidAPI-Host:'.config('app.api_host'),
            ],
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        $http_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($err) {
            $response = 'cURL Error #:'.$err;
        }

        return compact('http_response_code', 'response');
    }

    public static function formatDataForTable(string &$response, int $startDate, int $endDate): void
    {
        $response = collect(json_decode($response, 1)['prices'])
            ->whereBetween('date', [$startDate, $endDate])
            ->map(function ($data) {
                if(isset($data['open'], $data['high'], $data['low'], $data['close'], $data['volume'])){ //Filtering if the record is OHLC or split or other information
                    $temp[] = Carbon::createFromTimestampUTC($data['date'])->format('jS M, Y');
                    $temp[] = self::smart_number_format($data['open']);
                    $temp[] = self::smart_number_format($data['high']);
                    $temp[] = self::smart_number_format($data['low']);
                    $temp[] = self::smart_number_format($data['close']);
                    $temp[] = self::smart_number_format($data['volume'], false);
                    $temp[] = intval($data['date'].'000');
                    return $temp;
                }else{
                    return false;
                }
            })->reject(function ($element) {
                return $element === false;
            })->toArray();
    }

    public static function smart_number_format(mixed $value, bool $forcedFloat = true): mixed
    {
        if (((is_float($value) && ($value == intval($value))) || is_int($value)) && ! $forcedFloat) {
            return number_format($value);
        }
        if (is_float($value)) {
            return number_format(floatval($value), 4, '.', ',');
        }

        return $value;
    }

    public static function buildHttpQueryString(mixed $payload): string
    {
        if (empty($payload)) {
            return '';
        }
        $query_array = [];
        foreach ($payload as $key => $value) {
            $query_array[] = rawurlencode($key).'='.rawurlencode($value);
        }

        return '?'.implode('&', $query_array);
    }

    public static function prepareMailContents(Symbol $symbol, array $input): array
    {
        $data = [];
        $startDate = Carbon::parse($input['s_date'])->format('Y-m-d');
        $endDate = Carbon::parse($input['e_date'])->format('Y-m-d');
        $data['subject'] = "for submitted Company Symbol = {$symbol->symbol} => Company’s Name = {$symbol->c_name}";
        $data['body'] = 'Start Date and End Date'.PHP_EOL."From {$startDate} to {$endDate}";

        return $data;
    }
}
