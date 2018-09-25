<?
namespace app\sys\model;

use think\Model;

/**
 * Class LogType 
 *  
 * @property int id id 
 * @property int menu_id 所属菜单 
 * @property string name 日志唯一标识 
 * @property string title 日志类型名称 
 * @package app\sys\model 
 */
class LogType extends Model {
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