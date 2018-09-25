<?
namespace app\sys\model;

use think\Model;

/**
 * Class Log 
 *  
 * @property int id id 
 * @property int operator 操作人ID 
 * @property int lt_id lt_id 
 * @property string content content 
 * @property int create_time 操作时间 
 * @property string ip_addr ip_addr 
 * @property string operate_no 操作编号 
 * @package app\sys\model 
 */
class Log extends Model {
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