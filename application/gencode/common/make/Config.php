<?php
/**
 * Created by PhpStorm.
 * User: IGG
 * Date: 2018/9/6
 * Time: 9:52
 */

namespace app\gencode\common\make;

use app\gencode\common\Build;

class Config
{
    /**
     * 生成参数
     *
     * @var array
     */
    private $context;

    public function __construct($context)
    {
        $this->context = $context;
    }

    /**
     * 生成配置文件内容
     *
     * @return string 生成的文件名
     * @throws \Exception
     */
    public function make() {
        $module = $this->context['module'];
        $connection = $this->context['connection'];
        $table = $this->context['table'];

        $fields = Build::getTableFields($connection, $table);
        $config = [
            'connection' => $connection,
            'name' => $table,
            'table' => $table,
            'title' => $table,
            'batch' => true, // 批量操作
            'function' => 'add,edit,delete,detail,export',
            'fields' => [],
            'form' => [],
            'search' => [],
            'list' => [],
        ];

        $form = [];
        foreach ($fields as $field) {
            $config['fields'][$field['name']] = self::getFieldConfig($field);
            $config['list'][$field['name']] = self::getColumnConfig($field);
            if (!$field['is_pri'] || $field['name'] === 'create_time' || $field['name'] === 'update_time') {
                $form[] = $field['name'];
            }
        }

        $config['form']['default'] = implode(',', $form);

        $content = "<?php\r\nreturn ".var_export($config,TRUE).";\r\n";
        $content = Build::formatPhpCode($content);
        $fileName = Build::getTableName($connection, $table) . '.php';

        Build::writeFile($module, $fileName, $content);
        return $fileName;
    }

    /**
     * 取字段配置信息
     *
     * @param array $fieldInfo 字段结构
     * @return array
     */
    private function getFieldConfig(array $fieldInfo) {
        $fieldConfig = [
            'title' => $fieldInfo['comment'] ? $fieldInfo['comment'] : $fieldInfo['name'],
            'type' => $fieldInfo['type'],
            'max_length' => $fieldInfo['max_length'],
            'nullable' => $fieldInfo['nullable'],
            'default' => $fieldInfo['default'],
        ];

        $rule = [];
        if (!$fieldInfo['nullable']) {
            $rule[] = 'require';
        }

        $component = 'text';
        switch ($fieldInfo['type']) {
            case 'varchar':
                $rule[] = sprintf('max:%d', $fieldInfo['max_length']);
                break;
            case 'int':
            case 'tinyint':
            case 'smallint':
            case 'bigint':
                $rule[] = 'integer';
                break;
            case 'decimal':
                $rule[] = 'number';
                break;
            case 'date':
                $rule[] = 'date';
                $component = 'date';
                break;
            case 'datetime':
                $rule[] = 'date';
                $component = 'datetime';
                break;
            case 'text':
                $component = 'textarea';
                break;
        }

        if ($fieldInfo['is_pri']) {
            $component = 'hidden';
        }



        $fieldConfig['validate'] = $rule ? implode('|', $rule) : NULL;
        $fieldConfig['component'] = $component;
        $fieldConfig['attribute'] = $this->getComponentAttribute($component, $fieldInfo['max_length']);

        if ($fieldInfo['name'] === 'create_time' || $fieldInfo['name'] === 'update_time') {
            $fieldConfig['add_status'] = 'hide';
            $fieldConfig['edit_status'] = 'hide';
        }

        return $fieldConfig;
    }

    /**
     * 取字段的组件默认属性
     *
     * @param string $component 组件名
     * @param string $maxLength 字段最大长度
     * @return array
     */
    private function getComponentAttribute($component, $maxLength) {
        $attribute = [];
        if ($component === 'text' && $maxLength) {
            $attribute['maxlength'] = $maxLength;
        } elseif ($component === 'textarea') {
            if ($maxLength) {
                $attribute['maxlength'] = $maxLength;
            }

            $attribute['rows'] = 5;
        }

        return $attribute;
    }

    /**
     * 取列表列字段配置
     *
     * @param array $fieldInfo 字段结构
     * @return array
     */
    private function getColumnConfig(array $fieldInfo) {
        if ($fieldInfo['is_pri']) {
            return [
                'hide' => true,
            ];
        }

        $fieldConfig = [
            'width' => 10,
            'hide' => false,
            'head_align' => 'left',
            'body_align' => 'left',
        ];

        switch ($fieldInfo['type']) {
            case 'int':
            case 'tinyint':
            case 'smallint':
            case 'bigint':
                $fieldConfig['width'] = 3;
                break;
            case 'decimal':
                $fieldConfig['body_align'] = 'right';
                $fieldConfig['width'] = 3;
                break;
            case 'date':
            case 'datetime':
                $fieldConfig['width'] = 4;
                $fieldConfig['alias'] = sprintf('%s_text', $fieldInfo['name']);
                break;
        }

        return $fieldConfig;
    }
}