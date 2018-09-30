<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]
namespace think;

// 加载基础文件
require __DIR__ . '/../thinkphp/base.php';

// 支持事先使用静态方法设置Request对象和Config对象
if (\think\facade\Request::isOptions()) {
    header("Access-Control-Allow-Method: GET, POST, PUT, DEL");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, access_token");
    header('Access-Control-Allow-Origin: http://localhost:9527');
    header('Access-Control-Allow-Credentials: true');
    exit();
}

// 执行应用并响应
Container::get('app')->run()->send();
