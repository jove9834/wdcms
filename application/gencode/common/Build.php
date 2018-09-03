<?php
/**
 * Created by PhpStorm.
 * User: IGG
 * Date: 2018/8/20
 * Time: 16:48
 */

namespace app\gencode\common;


use think\Db;
use think\db\Query;

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
        if (!$connection || !$tableName) {
            throw new \Exception('无效参数');
        }

        $sql = 'select * from information_schema.columns where table_schema=:dbname and table_name=:tableName';
        $config = Db::getConfig($connection);
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
}