<?php
/**
 * <同类说明>
 *
 * @author    pengfeili
 * @copyright 2006-2017 IGG Inc.
 */
namespace app\common;

/**
 * http请求相关的封装
 *
 * 示范:
 * Http::setOptions(array(CURLOPT_TIMEOUT => 1));
 * $ret = Http::get('https://support.igg.com/t.php');
 * if ($ret === false) var_dump(Http::getLastRequestError());
 */
class Http
{
    /**
     * curl请求的option设置
     * 
     * @var array
     */
    private static $options;
    
    /**
     * GET请求
     * 
     * @param string $url 请求的的URL
     * @throws \Exception 异常
     * @return string
     */
    public static function get($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if (self::$options) {
            curl_setopt_array($ch, self::$options);
        }
        
        $ret = curl_exec($ch);
        if ($ret === FALSE) {
            throw new \Exception(curl_errno($ch), curl_error($ch));
        }

        curl_close($ch); 
        
        return $ret;
    }
    
    /**
     * POST请求
     * 
     * @param string $url   请求的的URL
     * @param array  $param 要传递的数据
     * @throws \Exception 请求异常
     * @return string
     */
    public static function post($url, array $param = NULL)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
        if (self::$options) {
            curl_setopt_array($ch, self::$options);
        }
        
        $ret = curl_exec($ch);
        if ($ret === FALSE) {
            throw new \Exception(curl_errno($ch), curl_error($ch));
        }
        
        curl_close($ch); 
        
        return $ret;
    }
    
    /**
     * 设置CURL的请求option
     * 
     * 最常见的是指定超时: array(CURLOPT_CONNECTTIMEOUT => 1, CURLOPT_TIMEOUT => 3)
     * 
     * @param array $options option设置列表, 格式参考函数:curl_setopt_array
     * @return void
     */
    public static function setOptions(array $options)
    {
        self::$options = $options;
    }
}