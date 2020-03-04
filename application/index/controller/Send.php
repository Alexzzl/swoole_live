<?php
namespace app\index\controller;

use app\common\Util;
use app\common\Redis;

class Send
{
    /*
    * send sms
    */
    public function index()
    {
        // $phoneNum =  request()->get('phone_num', 0, 'intval');
        $phoneNum =  intval($_GET['phone_num']);
        if(empty($phoneNum)){
            return Util::show(config('code.error'),'error'); 
        }

        //todo 
        // gen ram code
        // redis
        $code =  rand(1000, 9999);
        try{
            // send code
            $code_status = 'OK';
        } catch (\Exception $e){
            // todo
            return Util::show(config('code.error'),'inter error'); 
        }
        if($code_status=='OK'){
            // asyn redis
            $redis = new \Swoole\Coroutine\Redis();
            $redis->connect(config('redis.host'), config('redis.port'));
            $redis->set(Redis::smsKey($phoneNum), $code, config('redis.time_out'));

            return Util::show(config('code.success'),'send success');
        } else {
            return Util::show(config('code.error'),'send fail'); 
        }


    
    }
}