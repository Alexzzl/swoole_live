<?php
namespace app\common;

class Redis {
    // code pre
    public static $pre = "sms_";

    public static function smsKey($phone)
    {
        return self::$pre.$phone;
    }
}