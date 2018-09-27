<?
namespace app\sys\model;

use think\facade\Validate;
use think\Model;

/**
 * Class LoginAccount 
 *  
 * @property int id id 
 * @property int user_id 用户ID 
 * @property string account 账号 
 * @property int account_type 账号类型 
 * @package app\sys\model 
 */
class LoginAccount extends Model {
    /**
     * 账号类型 -- 用户名
     */
    const ACCOUNT_TYPE_USERNAME = 0;

    /**
     * 账号类型 -- 手机号码
     */
    const ACCOUNT_TYPE_MOBILE = 1;

    /**
     * 账号类型 -- 邮箱
     */
    const ACCOUNT_TYPE_EMAIL = 2;

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
    protected $append = array('func_text');
}