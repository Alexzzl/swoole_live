<?php
/**
 * all aysn task
 */
namespace app\common\task;

use app\common\Util;
use app\common\Redis;
use app\common\redis\Predis;

class Task {
    public function sendSms($data)
    {
        $phoneNum = $data['phone'];
        $code = $data['code'];

        try{
            // send code ali server 
            $code_status = 'OK';
        } catch (\Exception $e){
            // todo
            return false; 
        }
        if($code_status=='OK'){
            Predis::getInstance()->set(Redis::smsKey($phoneNum), $code, config('redis.expired'));
        } else {
            return false;
        }   
        return true;
        
        
    }
}