<?php
namespace app\common\redis;

class Predis
{

    public $redis = "";
    /**
     * 定义单例模式的变量
     */
    private static $_instance = null;

    public static function getInstance()
    {
        if(empty(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() 
    {
        $this->redis = new \Redis();
        $result = $this->redis->connect(config('redis.host'), config('redis.port'), config('redis.time_out'));
        if(!$result){
            throw new \Exceptipn('redis connect error');
        }
    }

    public function set($key, $value, $time = 0)
    {
        if(!$key){
            return '';
        }
        if(is_array($value)){
            $value = json_encode($value);
        }

        if(!$time) {
            return $this->redis->set($key, $value);   
        }

        return $this->redis->setex($key, $time, $value);
    }

    public function get($key)
    {
        if(!$key){
            return '';
        }

        return $this->redis->get($key);
    }
}