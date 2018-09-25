<?php
namespace app\sys\model;

use think\Validate;

class LogType extends Validate {
    protected $rule = array('id' => 'require|integer', 'menu_id' => 'require|integer', 'name' => 'max:40', 'title' => 'max:60');
    
    protected $message = array();
}
