<?
namespace app\sys\model;

use think\Model;

/**
 * Class Dict 
 *  
 * @property int id id 
 * @property string module 所属模块 
 * @property string name 字典标识 
 * @property string title 名称 
 * @property string item_source 数据源 
 * @property int create_time create_time 
 * @package app\sys\model 
 */
class Dict extends Model {
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