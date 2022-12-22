<?php

namespace App\Lib;

class DatatableResponder
{
    public static function sendResponse($data, $count = 0, int $http_status_code = 200, $headers = [])
    {
        return response()->json([
            'data' => $data,
            'totalData' => $count
        ], $http_status_code, $headers);
    }
}
