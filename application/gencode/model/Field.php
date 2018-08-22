<?php
namespace app\gencode\model;

use think\Model;

class Field extends Model
{
    /**
     * 验证类型
     */
    const VALIDATOR_LIST = [
        'required' => '必填'
    ];

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
    protected $append = ['validator_text'];

    /**
     * 功能
     *
     * @param string $value 功能值
     * @param mixed  $data  行数据
     * @return string
     */
    public function getValidatorTextAttr($value, $data)
    {
        if (!$data['validator']) {
            return '';
        }

        /**
         * 格式
         * [
         *  'required' => true,
         *  'max_length' => 255,
         *  'number' => true
         * ]
         */
        $validator = json_decode($data['validator'], TRUE);
        $ret = [];
        foreach ($validator as $name => $value) {
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

    /**
     * 数组转换为以逗号分隔的字符串
     * @param mixed $value 值
     * @return string
     */
    public function setValidatorAttr($value) {
        if (!$value) {
            return '';
        }

        if (is_array($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
        }

        return $value;
    }
}