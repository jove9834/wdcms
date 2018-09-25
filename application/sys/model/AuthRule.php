<?
namespace app\sys\model;

use think\Model;

/**
 * Class AuthRule 
 *  
 * @property int id ID 
 * @property string name 规则标识 
 * @property string title 规则名称 
 * @property int status 状态 
 * @property string condition 条件 
 * @package app\sys\model 
 */
class AuthRule extends Model {
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