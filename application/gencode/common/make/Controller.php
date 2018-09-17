<?php
/**
 * Created by PhpStorm.
 * User: IGG
 * Date: 2018/9/6
 * Time: 9:52
 */

namespace app\gencode\common\make;

use app\gencode\common\Build;

class Controller extends BuildCode
{
    /**
     * 生成控制器
     *
     * @return string 生成的文件名
     * @throws \Exception
     */
    public function make() {
        $module = $this->context['module'];
        $template = $this->context['template'];

        $template = 'gencode@' . $template . '/controller';
        // 构造模板文件
        $className = Build::getClassName($this->config['name'], 'controller');
        $this->config['className'] = $className;
        $this->config['package'] = Build::getPackageName($module, 'controller');
        $this->config['modelClassName'] = Build::getClassName($this->config['name']);
        $this->config['modelFullClassName'] = Build::getPackageName($module, 'model', $this->config['name']);
        $this->config['validateFullClassName'] = Build::getPackageName($module, 'validate', $this->config['name']);
        $response = view($template, $this->config);
        $content = "<?" . PHP_EOL . $response->getContent();
        $content = Build::formatPhpCode($content);
        $fileName = sprintf('controller\%s.php', $className);
        Build::writeFile($module, $fileName, $content);
        return $fileName;
    }
}