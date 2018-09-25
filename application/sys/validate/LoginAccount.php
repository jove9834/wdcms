<?php
namespace app\sys\model;

use think\Validate;

class LoginAccount extends Validate {
    protected $rule = array('id' => 'require|integer', 'user_id' => 'require|integer', 'account' => 'require|max:60', 'account_type' => 'require|integer');
    
    protected $message = array();
}
