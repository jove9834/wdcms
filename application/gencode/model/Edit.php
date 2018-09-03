<?php
namespace app\gencode\model;

use think\Model;

class Edit extends Model
{
    /**
     * 默认的创建时间字段为create_time，更新时间字段为update_time
     *
     * @var string
     */
    protected $autoWriteTimestamp = 'datetime';
    /**
     * 别名属性
     *
     * @var array
     */
    protected $append = ['component_name', 'attribute_text'];

    /**
     * 组件名称
     *
     * @param string $value value
     * @param mixed  $data  行数据
     * @return string
     */
    public function getComponentNameAttr($value, $data)
    {
        if (!$data['component']) {
            return '';
        }

        $components = config('component');

        return $components[$data['component']];
    }

    /**
     * 组件属性名称
     *
     * @param string $value value
     * @param mixed  $data  行数据
     * @return string
     */
    public function getAttributeTextAttr($value, $data)
    {
        if (!$data['attribute']) {
            return '';
        }

        $attribute = json_decode($data['attribute'], TRUE);
        $ret = [];
        foreach ($attribute as $name => $value) {
            if (is_bool($value)) {
                $ret[] = $name;
            } else if (is_array($value)) {
                $ret[] = sprintf('%s: %s', $name, implode(', ', $value));
            } else {
                $ret[] = sprintf('%s: %s', $name, $value);
            }
        }

        return implode('|', $ret);
    }
}