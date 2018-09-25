<?php
namespace app\sys\model;

use think\Validate;

class DictItem extends Validate {
    protected $rule = array('id' => 'require|integer', 'dict_id' => 'require|integer', 'value' => 'require|max:45', 'title' => 'require|max:60', 'pid' => 'require|integer', 'path' => 'require|max:60', 'display_order' => 'require|integer');
    
    protected $message = array();
}
