<?php
namespace app\gencode\model;

use think\Model;

class Page extends Model
{
    /**
     * 默认的创建时间字段为create_time，更新时间字段为update_time
     *
     * @var string
     */
    protected $autoWriteTimestamp = 'datetime';

    protected $append = ['func_text'];

    /**
     * 功能
     *
     * @param string $value 功能值
     * @param mixed  $data  行数据
     * @return string
     */
    public function getFuncTextAttr($value, $data)
    {
        if (!$data['func']) {
            return '';
        }

        $func = [1 => '多选功能', 2 => '查询条件', 3 => '添加', 4 => '修改', 5 => '删除'];
        $arr = explode(',', $data['func']);
        $ret = [];
        foreach ($arr as $item) {
            if (isset($func[$item])) {
                $ret[] = $func[$item];
            }
        }

        return implode(', ', $ret);
    }
}