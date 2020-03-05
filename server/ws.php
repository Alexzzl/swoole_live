<?php

class Ws {
    CONST HOST = "0.0.0.0";
    CONST PORT = 8811;

    public $ws = null;
    
    public function __construct()
    {
        $this->ws = new swoole_websocket_server(self::HOST, self::PORT);

        $this->ws->set([
            "enable_static_handler" => true,
            "document_root" => "/home/alex/study/thinkphp/public/static/",
            'worker_num' => 2,
            'task_worker_num' => 2,

        ]);

        $this->ws->on("open", [$this, 'onOpen']);
        $this->ws->on("message", [$this, 'onMessage']);
        $this->ws->on("WorkerStart", [$this, 'onWorkerStart']);
        $this->ws->on("request", [$this, 'onRequest']);
        $this->ws->on("task", [$this, 'onTask']);
        $this->ws->on("finish", [$this, 'onFinish']);
        $this->ws->on("close", [$this, 'onClose']);


        $this->ws->start();
    }


    public function onWorkerStart(swoole_server $server, $work_id)
    {
        // 定义应用目录
        define('APP_PATH', __DIR__ . '/../application/');
        // 加载框架引导文件
        // ThinkPHP 引导文件
        // 1. 加载基础文件
        // require __DIR__ . '/../thinkphp/base.php';
        require __DIR__ . '/../thinkphp/start.php';
    }

    
    public function onRequest($request, $response)
    {
        $_SERVER = [];
        if(isset($request->server)) {
            foreach($request->server as $key => $value) {
                $_SERVER[strtoupper($key)] = $value;
            }
        }
        if(isset($request->header)) {
            foreach($request->header as $key => $value) {
                $_SERVER[strtoupper($key)] = $value;
            }
        }
    
        $_GET = [];
        if(isset($request->get)){
            foreach($request->get as $key => $value) {
                $_GET[$key] = $value;
            }
        }
        $_POST = [];
        if(isset($request->post)){
            foreach($request->post as $key => $value) {
                $_POST[$key] = $value;
            }
        }
        $_POST['ws_server'] = $this->ws;
        
        ob_start();
        // 2. 执行应用
        try{
            think\App::run()->send();
        }catch(\Exception $e){
            // todo
        }
        
    
        $res = ob_get_contents();
        ob_end_clean();
    
        $response->end($res);
        // $http->close();
   
    }

    // Listen WebSocket connecting
    public function onOpen($ws, $request)
    {
        // if($request->fd == 1) {
        //     // Swoole\Timer::tick(2000, function($timer_id){
        //     //     echo "2s: timerId: {$timer_id}\n";
        //     // });
        // }
        // echo "server: handshake success with fd{$request->fd}\n";
        // var_dump($request->fd);
    }

    // Listen WebSocket Message
    public function onMessage($ws, $frame)
    {
        // Swoole\Timer::after(5000, function() use ($ws, $frame){
        //    echo "5s after:\n";
        //    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
        //    $ws->push($frame->fd, "5s after server push:".date("Y-m-d H:i:s"));
        // });
        $ws->push($frame->fd, "this is alex push server, time:".date("Y-m-d H:i:s"));
   
    }

    public function onTask($serv, $taskId, $workerId, $data)
    {
        // task 
        $obj = new app\common\task\Task;
        if(!isset($data['method'])){
            return false;
        }
        $method = $data['method'];
        $flag = $obj->$method($data['data']);
    
        /*try{
            // send code ali server 
            $send = new app\index\controller\Send();
            $result = $send::sendRedis($data['phone'], $data['code']);
        } catch (\Exception $e){
            // todo
            // return Util::show(config('code.error'),'inter error'); 
            echo $e->getMessage();
        }*/
        
        return $flag;

    }

    public function onFinish($serv, $taskId, $data)
    {
        echo "taskId:{$taskId}\n";
        echo "finish-data-success:{$data}\n";
    }

    public function onClose($ws, $fd)
    {
        echo "client {$fd} closed\n";
    }

}

new Ws();