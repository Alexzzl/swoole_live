<?php


$http = new Swoole\Http\Server("0.0.0.0", 8811);

$http->set([
    "enable_static_handler" => true,
    "document_root" => "/home/alex/study/thinkphp/public/static/",
    "worker_num"=>5
]);
$http->on('WorkerStart', function(swoole_server $server, $work_id){
    // 定义应用目录
    define('APP_PATH', __DIR__ . '/../application/');
    // 加载框架引导文件
    // ThinkPHP 引导文件
    // 1. 加载基础文件
    require __DIR__ . '/../thinkphp/base.php';
    // require __DIR__ . '/../thinkphp/start.php';
    
});
$http->on('request', function($request, $response) use ($http){
    // // 定义应用目录
    // define('APP_PATH', __DIR__ . '/../application/');
    // // 加载框架引导文件
    // // ThinkPHP 引导文件
    // // 1. 加载基础文件
    // require_once __DIR__ . '/../thinkphp/base.php';
    // // require __DIR__ . '/../thinkphp/start.php';

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
    
    ob_start();
    // 2. 执行应用
    try{
        think\App::run()->send();
    }catch(\Exception $e){
        // todo
    }
    // echo "-action:".request()->action().PHP_EOL;

    $res = ob_get_contents();
    ob_end_clean();

    $response->end($res);
    // $http->close();

});

$http->start();