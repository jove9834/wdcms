<?php
/**
 * Created by PhpStorm.
 * User: IGG
 * Date: 2018/9/6
 * Time: 9:52
 */

namespace app\gencode\common\make;

use app\gencode\common\Build;

class Model extends BuildCode
{
    /**
     * 生成模型
     *
     * @return string 生成的文件名
     * @throws \Exception
     */
    public function make() {
        $module = $this->context['module'];
        $template = $this->context['template'];
        $template = 'gencode@' . $template . '/model';
        $className = Build::getClassName($this->config['name']);
        $this->config['className'] = $className;
        $this->config['package'] = Build::getPackageName($module, 'model');
        $response = view($template, $this->config);
        $content = "<?" . PHP_EOL . $response->getContent();
        $content = Build::formatPhpCode($content);
        $fileName = sprintf('model\%s.php', $className);
        Build::writeFile($module, $fileName, $content);
        return $fileName;
    }
}