<?php
namespace app\index\controller;

use app\common\Util;
use app\common\Redis;
use app\common\redis\Predis;

class Login
{
    /*
    * send sms
    */
    public function index()
    {
        // phone code
        $phoneNum = intval($_GET['phone_num']);
        $code = intval($_GET['code']);
        if(empty($phoneNum) || empty($code)){
            return Util::show(config('code.error'), 'phone or code is error');
        }

        // redis exist
        try {
            $redisCode = Predis::getInstance()->get(Redis::smsKey($phoneNum));
            if(!$redisCode){
                return Util::show(config('code.error'), 'get redis fail',$redisCode);
            }

            if($redisCode == $code){
                // redis
                $data = [
                    'user'=>$phoneNum,
                    'srcKey'=>md5(Redis::userKey($phoneNum)),
                    'time' => time(),
                    'isLogin' =>true,
                ];
                $result = Predis::getInstance()->set(Redis::userKey($phoneNum), $data);
                if(!$result){
                    return Util::show(config('code.error'), 'login fail, set fail ',$data);    
                }
                return Util::show(config('code.success'), 'ok',$data);
            } else {
                return Util::show(config('code.error'), 'login fail');
            }
        } catch (\Exception $e) {
            return Util::show(config('code.error'), $e->getMessage());
        }
        
        


    
    }
}