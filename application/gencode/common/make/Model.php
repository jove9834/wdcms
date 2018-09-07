<?php
/**
 * Created by PhpStorm.
 * User: IGG
 * Date: 2018/9/6
 * Time: 9:52
 */

namespace app\gencode\common\make;

use app\gencode\common\Build;

class Model
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
        $template = 'gencode@' . $template . '/model';
        // 构造模板文件
        $className = $this->getClassName();
        $this->config['className'] = $className;
        $this->config['package'] = $this->getPackageName('model');
        $response = view($template, $this->config);
        $content = "<?" . PHP_EOL . $response->getContent();
        $content = Build::formatPhpCode($content);
        $fileName = sprintf('model\%s.php', $className);
        Build::writeFile($module, $fileName, $content);
        return $fileName;
    }

    /**
     * 获取包名
     *
     * @param string $type         类型， controller, model
     * @return string
     */
    private function getPackageName($type) {
        if ($type === 'controller') {
            $package = sprintf('app\%s\controller', $this->context['module']);
        } else {
            $package = sprintf('app\%s\model', $this->context['module']);
        }

        return $package;
    }

    /**
     * 获取类名
     *
     * @return string
     */
    private function getClassName() {
        return parse_name($this->config['name'], 1);
    }

    private function makeValidateRule($config) {
        $rules = [];
        foreach ($config['fields'] as $field) {
            if (isset($field['validate'])) {
                if (is_string($field['validate'])) {
                    
                }
            }
        }
    }
}