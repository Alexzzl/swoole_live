<?php
namespace app\common;

class Redis {
    // code pre
    public static $code_pre = "sms_";

    // user pre
    public static $user_pre = "user_";

    public static function smsKey($phone)
    {
        return self::$code_pre.$phone;
    }

    public static function userKey($phone)
    {
        return self::$user_pre.$phone;
    }
}