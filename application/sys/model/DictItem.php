<?
namespace app\sys\model;

use think\Model;

/**
 * Class DictItem 
 *  
 * @property int id id 
 * @property int dict_id dict_id 
 * @property string value 项名（key） 
 * @property string title 标题 
 * @property int pid pid 
 * @property string path path 
 * @property int display_order 排序号 
 * @package app\sys\model 
 */
class DictItem extends Model {
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