<?php
namespace app\sys\model;

use think\Validate;

class Dict extends Validate {
    protected $rule = array('id' => 'require|integer', 'module' => 'require|max:20', 'name' => 'require|max:20', 'title' => 'require|max:100', 'item_source' => 'require|max:100', 'create_time' => 'require|integer');
    
    protected $message = array();
}
