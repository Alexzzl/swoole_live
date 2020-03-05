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
            'method' => 'sendSms',
            'data'=>[
                'phone'=>$phoneNum,
                'code'=>$code,
            ],            
        ];
        $_POST['http_server']->task($taskData);


        return Util::show(config('code.success'),'send success', $code);


    
    }

}