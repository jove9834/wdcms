<?
namespace app\sys\model;

use think\Model;

/**
 * Class User 
 *  
 * @property int id id 
 * @property string real_name 姓名 
 * @property string password 密码 
 * @property string salt 加密代码 
 * @property int gender 性别 
 * @property string avatar 头像 
 * @property string region 所在地区 
 * @property string reg_ip reg_ip 注册IP
 * @property string first_letter 首字母
 * @property int status 状态，1 正常 0 禁用 
 * @property int create_time 创建时间
 * @package app\sys\model 
 */
class User extends Model {
    /**
     * 用户状态 -- 禁用
     */
    const USER_STATUS_DISABLED = 0;

    /**
     * 用户状态 -- 正常
     */
    const USER_STATUS_ENABLED = 1;

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