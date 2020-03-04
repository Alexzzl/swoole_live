<?php
namespace app\common;

class Util
{
    // API format
    public static function show($status, $message = '',$data = [])
    {
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];

        echo json_encode($result);
    }
}