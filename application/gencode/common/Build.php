<?php
/**
 * Created by PhpStorm.
 * User: IGG
 * Date: 2018/8/20
 * Time: 16:48
 */

namespace app\gencode\common;


use think\Container;
use think\Db;
use think\db\Query;
use think\facade\Log;

class Build
{
    public static function getConfig($id) {
        $config = [];
        return $config;
    }

    /**
     * 生成代码
     *
     * @param array  $config   配置信息
     * @param string $template 模板
     * @param string $type     代码类型
     */
    public static function buildCode($config, $template, $type) {

    }

    /**
     * 取表字段信息
     *
     * @param string $connection 数据库连接配置名
     * @param string $tableName  表名
     * @return array
     * @throws \Exception 异常
     */
    public static function getTableFields($connection, $tableName) {
        if (!$tableName) {
            throw new \Exception('无效参数');
        }

        $sql = 'select * from information_schema.columns where table_schema=:dbname and table_name=:tableName';
        $config = Db::getConfig($connection);
        Log::debug($config);
        /** @var Query $query */
        $query = Db::connect($config);
        $bind = array(
            'dbname' => $query->getConfig('database'),
            'tableName' => $query->getConfig('prefix') . $tableName
        );

        $rows = $query->query($sql, $bind);
        $fields = [];
        if ($rows) {
            foreach ($rows as $item) {
                $name = $item['COLUMN_NAME'];
                $fields[$name] = [
                    'name' => $name,
                    'type' => $item['DATA_TYPE'],
                    'max_length' => $item['CHARACTER_MAXIMUM_LENGTH'],
                    'comment' => $item['COLUMN_COMMENT'],
                    'nullable' => $item['IS_NULLABLE'] === 'YES',
                    'default' => $item['COLUMN_DEFAULT'],
                    'is_pri' => $item['COLUMN_KEY'] === 'PRI',
                ];
            }
        }

        return $fields;
    }

    /**
     * 生成配置文件内容
     *
     * @param $module
     * @param $connection
     * @param $table
     * @return string 生成的文件名
     * @throws \Exception
     */
    public static function makeConfig($module, $connection, $table) {
        $fields = self::getTableFields($connection, $table);
        $config = [
            'connection' => $connection,
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
        $fileName = self::getTableName($connection, $table) . '.php';

        self::writeFile($module, $fileName, $content);
        return $fileName;
    }

    private static function getTableName($connection, $table) {
        $config = Db::getConfig($connection);
        $prefix = $config['prefix'];
        if (substr($table, 0, strlen($prefix)) === $prefix) {
            return substr($table, strlen($prefix));
        }

        return $table;
    }

    /**
     * 写文件
     *
     * @param $module
     * @param $fileName
     * @param $content
     */
    private static function writeFile($module, $fileName, $content) {
        $pathname = Container::get('app')->getRuntimePath() . 'gencode' .DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $fileName;

        if (!is_dir(dirname($pathname))) {
            mkdir(dirname($pathname), 0755, true);
        }

        file_put_contents($pathname, $content);
    }

    /**
     * 取字段配置信息
     *
     * @param array $fieldInfo 字段结构
     * @return array
     */
    private static function getFieldConfig(array $fieldInfo) {
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
        $fieldConfig['attribute'] = self::getComponentAttribute($component, $fieldInfo['max_length']);

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
    private static function getComponentAttribute($component, $maxLength) {
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
    private static function getColumnConfig(array $fieldInfo) {
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