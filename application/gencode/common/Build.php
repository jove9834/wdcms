<?php
/**
 * Created by PhpStorm.
 * User: IGG
 * Date: 2018/8/20
 * Time: 16:48
 */

namespace app\gencode\common;


use app\common\Http;
use think\Container;
use think\Db;
use think\db\Query;
use think\facade\Log;

class Build
{
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

    public static function getConfigFile($module, $table) {
        $fileName = $table . '.php';
        $pathname = Container::get('app')->getRuntimePath() . 'gencode' .DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $fileName;
        if (!file_exists($pathname)) {
            throw new \Exception('配置文件不存在');
        }

        return $pathname;
    }

    /**
     * 取生成的配置文件
     *
     * @param string $module 模块名称
     * @param string $table  表名
     * @return mixed
     * @throws \Exception
     */
    public static function getConfig($module, $table) {
        $configFile = self::getConfigFile($module, $table);
        return include_once $configFile;
    }


    public static function getTableName($connection, $table) {
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
    public static function writeFile($module, $fileName, $content) {
        $pathname = Container::get('app')->getRuntimePath() . 'gencode' .DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $fileName;

        if (!is_dir(dirname($pathname))) {
            mkdir(dirname($pathname), 0755, true);
        }

        file_put_contents($pathname, $content);
    }

    /**
     * 取字段转为属性类型
     *
     * @param mixed $field 字段属性
     * @return string
     */
    public static function propertyType($field) {
        $fieldType = $field['type'];
        switch ($fieldType) {
            case 'int':
            case 'tinyint':
            case 'smallint':
            case 'bigint':
                $type = 'int';
                break;
            case 'decimal':
            case 'float':
                $type = 'float';
                break;
            default:
                $type = 'string';
        }

        return $type;
    }

    /**
     * 取字段的验证规则配置
     *
     * @param mixed $config 配置
     * @return array
     */
    public static function getFieldRules($config) {
        if (!$config) {
            return [];
        }

        $rules = [];
        $formFields = isset($config['form']['fields']) ? $config['form']['fields'] : [];
        $fields = isset($config['fields']) ? $config['fields'] : [];
        foreach ($fields as $name => $attr) {
            if (isset($formFields[$name]) && isset($formFields[$name]['validate'])) {
                // 以表单的配置为主
                $rule = $formFields[$name]['validate'];
            } elseif (isset($attr['validate'])) {
                $rule = $attr['validate'];
            } else {
                continue;
            }

            if (is_array($rule)) {
                $rules[$name] = $rule;
            } else {
                $rules[$name] = ['rule' => $rule];
            }
        }
    }

    /**
     * 获取包名
     *
     * @param string $module    模块名
     * @param string $type      类型， controller, model, validate
     * @param string $className 类名
     * @return string
     */
    public static function getPackageName($module, $type = 'controller', $className = '') {
        $module = trim($module);
        $type = trim($type);
        $className = trim($className);
        if (!$module || !$type) {
            return NULL;
        }

        if ($className) {
            $className = $type === 'controller' ? $className . 'Controller' : $className;
            return sprintf('app\%s\%s\%s', $module, $type, $className);
        } else {
            return sprintf('app\%s\%s', $module, $type);
        }
    }

    /**
     * 格式化代码
     *
     * @param string $code php代码
     * @return string
     * @throws \Exception
     */
    public static function formatPhpCode($code) {
        $params = array(
            'spaces_around_map_operator' => 'on',
            'spaces_around_assignment_operators' => 'on',
            'spaces_around_bitwise_operators' => 'on',
            'spaces_around_relational_operators' => 'on',
            'spaces_around_equality_operators' => 'on',
            'spaces_around_logical_operators' => 'on',
            'spaces_around_math_operators' => 'on',
            'space_after_structures' => 'on',
            'align_assignments' => 'on',
            'indent_case_default' => 'on',
            'indent_number' => '4',
            'first_indent_number' => '0',
            'indent_char' => ' ',
            'indent_style' => 'K&R',
            'code' => $code
        );

        $res = Http::post(
            'http://www.phpformatter.com/Output/',
            $params
        );

        $res = json_decode($res, TRUE);
        if (! $res["error"]) {
            return $res['plainoutput'];
        } else {
            return sprintf('Error on line %d: %s!', $res['line'], $res['text']);
        }
    }
}