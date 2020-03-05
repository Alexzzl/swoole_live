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

        $taskData = [
            'phone'=>$phoneNum,
            'code'=>$code,
        ];
        $_POST['http_server']->task($taskData);
        // try{
        //     // send code ali server 
        //     $code_status = 'OK';
        // } catch (\Exception $e){
        //     // todo
        //     return Util::show(config('code.error'),'inter error'); 
        // }
        // if($code_status=='OK'){
        //     // asyn redis
            $redis = new \Swoole\Coroutine\Redis();
            $redis->connect(config('redis.host'), config('redis.port'));
            $redis->set(Redis::smsKey($phoneNum), $code, config('redis.expired'));

            return Util::show(config('code.success'),'send success', $code);
        // } else {
        //     return Util::show(config('code.error'),'send fail'); 
        // }


    
    }

    public static function sendRedis($phoneNum,$code)
    {
        
        // $redis = new \Redis();
        // $redis->connect(config('redis.host'), config('redis.port'));
        // $result = $redis->set(Redis::smsKey($phoneNum), $code, config('redis.expired'));
    
        
        
        return [$phoneNum,$code];
    }
}