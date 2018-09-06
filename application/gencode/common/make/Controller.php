<?php
/**
 * Created by PhpStorm.
 * User: IGG
 * Date: 2018/9/6
 * Time: 9:52
 */

namespace app\gencode\common\make;

use app\gencode\common\Build;

class Controller
{
    /**
     * 配置信息
     */
    private $config;

    private $template;

    private $module;

    public function __construct($config, $template, $module)
    {
        $this->config = $config;
        $this->template = $template;
        $this->module = $module;
    }

    /**
     * 生成控制器
     *
     * @return string 生成的文件名
     * @throws \Exception
     */
    public function make() {
        $template = 'gencode@' . $this->template . '/controller';
        // 构造模板文件
        $className = parse_name($this->config['table'], 1) . 'Controller';
        $this->config['className'] = $className;
        $response = view($template, $this->config);
        $content = "<?" . PHP_EOL . $response->getContent();
        $content = Build::formatPhpCode($content);
        $fileName = $className . '.php';
        Build::writeFile($this->module, $fileName, $content);
        return $fileName;
    }

}