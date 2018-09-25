<?
namespace app\sys\model;

use think\Model;

/**
 * Class AuthGroup 
 *  
 * @property int id ID 
 * @property string title 用户组名 
 * @property int status 状态 
 * @property string rules 用户组拥有的规则 
 * @package app\sys\model 
 */
class AuthGroup extends Model {
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