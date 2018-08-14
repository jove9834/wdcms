<?php
/**
 * 公共函数
 *
 * @author    jove9834
 * @copyright Copyright (c) 2018, Wedo, Inc. (http://www.wdeip.com/)
 * @link      http://wdeip.com
 * @since     Version 1.0
 */

/**
 * 输出错误JSON
 *
 * @param string  $message    错误信息
 * @param integer $code       错误代码
 * @param array   $extra      额外数据
 * @param integer $httpStatus Http状态码，默认500
 * @return \think\response\Json
 */
function responseErrorJson($message, $code = 500, array $extra = array(), $httpStatus = 500){
//    header("Access-Control-Allow-Method: GET, POST, PUT, DEL");
//    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
//    header('Access-Control-Allow-Origin: http://localhost:9090');
//    header('Access-Control-Allow-Credentials: true');
    $json = array();
    $json["error"] = array();
    $json["error"]["code"] = $code ?: 500;
    $json["error"]["message"] = $message;
    if(is_array($extra) && $extra){
        foreach ($extra as $k => $v){
            $json["error"][$k] = $v;
        }
    }

    return json($json, $httpStatus);
}

/**
 * 输出数据JSON
 *
 * @param mixed   $data       数据
 * @param integer $httpStatus Http状态码，默认200
 * @return \think\response\Json
 */
function responseDataJson($data, $httpStatus = 200){
//    header("Access-Control-Allow-Method: GET, POST, PUT, DEL");
//    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
//    header('Access-Control-Allow-Origin: http://localhost:9090');
//    header('Access-Control-Allow-Credentials: true');

    $json = array();
    $json["data"] = $data;
    return json($json, $httpStatus);
}
