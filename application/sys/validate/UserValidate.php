<?php
namespace app\sys\model;

use think\Validate;

class UserValidate extends Validate {
    protected $rule = array('id' => 'require|integer', 'real_name' => 'require|max:80', 'password' => 'require|max:40', 'salt' => 'require', 'gender' => 'integer', 'avatar' => 'max:255', 'region' => 'max:10', 'status' => 'require|integer', 'create_time' => 'require|integer');
    
    protected $message = array();
}
