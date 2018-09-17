<?php
/**
 * Created by PhpStorm.
 * User: IGG
 * Date: 2018/9/6
 * Time: 9:52
 */

namespace app\gencode\common\make;

use app\gencode\common\Build;

class ListPage extends BuildCode
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

        $template = 'gencode@' . $template . '/validate';
        // 构造模板文件
        $className = Build::getClassName($this->config['name']);
        $this->config['className'] = $className;
        $this->config['package'] = Build::getPackageName($module, 'model');
        // 取验证规则
        $rules = Build::getFieldRules($this->config);
        $this->config['rules'] = $rules ? $rules : [];
        $response = view($template, $this->config);
        $content = "<?php" . PHP_EOL . $response->getContent();
        $content = Build::formatPhpCode($content);
        $fileName = sprintf('page\%s.php', $className);
        Build::writeFile($module, $fileName, $content);
        return $fileName;
    }
}