<?
namespace app\sys\model;

use think\Model;

/**
 * Class AuthGroupAccess 
 *  
 * @property int user_id user_id 
 * @property int group_id group_id 
 * @package app\sys\model 
 */
class AuthGroupAccess extends Model {
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