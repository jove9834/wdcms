<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/21
 * Time: 20:56
 */

namespace app\index\common;


use app\common\Http;

class PaySdk
{
    const API_URL = "https://unipayqrcode.ysepay.com/yspos-qr-gate";

    const MERKEY = '';

    const IMG_TYPE_IDCARD_1 = '001'; // 身份证正面
    const IMG_TYPE_IDCARD_2 = '002'; // 身份证反面
    const IMG_TYPE_IDCARD_3 = '005'; // 手持身份证
    const IMG_TYPE_BL = '012'; // 营业执照
    const IMG_TYPE_DHP = '013'; // 门头照
    const IMG_TYPE_OL = '014'; // 开户许可证


    /**
     * 注册
     *
     * @param string $agtNo  服务商编号
     * @param string $mobile 手机号
     * @return string
     * @throws \Exception 异常
     */
    public static function register($agtNo, $mobile) {
        $params = [
            'agtNo' => $agtNo,
            'mobile' => $mobile,
            'requestNo' => self::getRequestNo()
        ];

        $params = self::sign($params);
        $data = self::doPost('/merchant/userRegister', $params);
        return $data['userCode'];
    }

    public static function addCardOpenAccount($param) {

    }

    public static function upActiveUnion($userCode, $mobile) {

    }

    public static function uploadImage($userCode, $imgType, $filePath) {
        // busiCode=vs &fileType=images&userCode=M13561313251
        $randomInt = random_int(1, 99999) * 100000;
        $imgsn = sprintf('YST%d78965478525%d', time(), $randomInt);
        // userCode + ‘_’ + imgsn + ‘_imgtype.jpg’
        $fileName = sprintf('%s_$s_%s.jpg', $userCode, $imgsn, $imgType);
        $params = [
            'busiCode' => 'vs',
            'fileType' => 'images',
            'userCode' => $userCode,
        ];

        if(class_exists("\CURLFile")){
            $postFields["fileToUpload"] = new \CURLFile($filePath);
        }else{
            $postFields["fileToUpload"] = "@" . $filePath;
        }

        $headers = array("Content-Type:multipart/form-data");
        $url = http_build_url('https://u.ysepay.com:7188/yst-fileud/servlet/ReUploadHandleServlet', $params);

        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_POST => TRUE,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SAFE_UPLOAD => FALSE,
            CURLOPT_TIMEOUT => 120,
        );
        curl_setopt_array($ch, $options);
        $content = curl_exec($ch);
        $errorCode = curl_errno($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if (trim($content) != "") {
            $json = json_decode($content, TRUE);
        } else {
            $json = array("Error" => "API Error(" . $errorCode . "): " . $error);
        }

    }

    /**
     * 提交
     *
     * @param string $uri    URI
     * @param array  $params 参数
     * @return array
     * @throws \Exception 异常
     */
    public static function doPost($uri, array $params) {
        $url = self::API_URL . $uri;
        try {
            $res = Http::post($url, $params);
            $res = json_decode($res, true);
            if ($res['respCode'] === '0000') {
                return $res['data'];
            } else {
                throw new \Exception($res['respDesc'], intval($res['respCode']));
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 检查签名是否有效
     *
     * @param array $params 数据数组
     * @return array
     */
    public static function sign(array $params){
        unset($params["sign"]);
        ksort($params);
        $queryStr = http_build_query($params);
        $params["sign"] = md5($queryStr . '&' . self::MERKEY);
        return $params;
    }

    /**
     * 取请求流水号
     *
     * @return string
     */
    public static function getRequestNo() {
        return md5('厉害了我的国' . microtime(true));
    }

}