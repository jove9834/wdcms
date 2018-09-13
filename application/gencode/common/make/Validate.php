<?php
/**
 * Created by PhpStorm.
 * User: IGG
 * Date: 2018/9/6
 * Time: 9:52
 */

namespace app\gencode\common\make;

use app\gencode\common\Build;

class Validate
{
    /**
     * 生成参数
     *
     * @var array
     */
    private $context;

    private $config;

    /**
     * Controller constructor.
     * @param $context
     */
    public function __construct($context)
    {
        $this->context = $context;
    }

    /**
     * 生成模型
     *
     * @return string 生成的文件名
     * @throws \Exception
     */
    public function make() {
        $module = $this->context['module'];
        $table = $this->context['table'];
        $template = $this->context['template'];

        $this->config = Build::getConfig($module, $table);
        $template = 'gencode@' . $template . '/validate';
        // 构造模板文件
        $className = $this->getClassName();
        $this->config['className'] = $className;
        $this->config['package'] = Build::getPackageName($module, 'model');
        // 取验证规则
        $rules = Build::getFieldRules($this->config);
        $this->config['rules'] = $rules;
        $response = view($template, $this->config);
        $content = "<?" . PHP_EOL . $response->getContent();
        $content = Build::formatPhpCode($content);
        $fileName = sprintf('validate\%s.php', $className);
        Build::writeFile($module, $fileName, $content);
        return $fileName;
    }

    /**
     * 获取类名
     *
     * @return string
     */
    private function getClassName() {
        return parse_name($this->config['name'], 1);
    }
}