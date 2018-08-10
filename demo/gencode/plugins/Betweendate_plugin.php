<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *	Plugin Name: 时间段，用于查询
 *	Plugin URI: 
 *	Description: 
 *	Version: 0.1
 *	Author: Huangwj
 *	Author Email: jove9834@126.com
*/

class Betweendate_plugin
{
	private $_CI;

	public function __construct(&$plugin)
	{
		$plugin->register('Module::Field::betweendate', $this, 'build');
		
		$this->_CI = &get_instance();
	}

	/**
	*
	*
	*
	*/
	public function build($setting, $page)
	{
		$field = $setting['field'];
		$status = at_array_val($setting, $page.'_status','');

		$custom_field = at_array_val($setting, 'custom_field');
		$var_name = "search";
		$field = ($field == '0')?$custom_field:$field;


		$value = "";
		$style = "";

		if( !at_array_val($setting, 'width') )
		{
			$style = 'style="width:'.$setting['width'].'px"';
		}
		$html = '<div class="between-date">'.PHP_EOL;
		$html .= '	<div class="datepicker span5" id="GE_'.$field.'" '.$style.'>'.PHP_EOL;
		$html .= '		<a href="javascript:;" class="datepicker-btn"></a>'.PHP_EOL;
		$html .= '		<input type="text" class="form-control" name="GE_'.$field.'" readonly="">'.PHP_EOL;
		$html .= '	</div>'.PHP_EOL;
		$html .= '	<div class="between-symbol">〜</div>'.PHP_EOL;
		$html .= '	<div class="datepicker span5" id="LE_'.$field.'" '.$style.'>'.PHP_EOL;
		$html .= '		<a href="javascript:;" class="datepicker-btn"></a>'.PHP_EOL;
		$html .= '		<input type="text" class="form-control" name="LE_'.$field.'" readonly="">'.PHP_EOL;
		$html .= '	</div>'.PHP_EOL;
		$html .= '</div>'.PHP_EOL;


		$format = $setting['format'];
		//脚本
		$script_file = '<link href="{base_url(\'assets/js/datetimepicker/css/bootstrap-datetimepicker.css\')}" type="text/css" rel="stylesheet"/>'.PHP_EOL;
		$script_file .= '<script type="text/javascript" src="{base_url(\'assets/js/datetimepicker/js/bootstrap-datetimepicker.js\')}"></script>'.PHP_EOL;
		
		switch ($format) {
			case 'datetime':
				$param = "{format: 'yyyy-mm-dd hh:ii:ss'}";
				break;
			case 'time':
				$param = "{format: 'hh:ii:ss'}";
				break;
			default:
				$param = "";
				break;
		}

		$script = '$("#GE_'.$field.'").datepicker('.$param.');'.PHP_EOL;
		$script .= '$("#LE_'.$field.'").datepicker('.$param.');'.PHP_EOL;
		
		
		return array($html, $script, $script_file, 'show');
	}
}

/* End of file Date.php */
/* Location: ./apps/gencode/plugins/Date.php */
