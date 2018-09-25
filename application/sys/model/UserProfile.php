<?
namespace app\sys\model;

use think\Model;

/**
 * Class UserProfile 
 *  
 * @property int id id 
 * @property int user_id user_id 
 * @property string name 属性名 
 * @property string value 属性值 
 * @package app\sys\model 
 */
class UserProfile extends Model {
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