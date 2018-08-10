<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *	Plugin Name: 日期输入框
 *	Plugin URI: 
 *	Description: 
 *	Version: 0.1
 *	Author: Huangwj
 *	Author Email: jove9834@126.com
*/

class Date_plugin
{
	private $_CI;

	public function __construct(&$plugin)
	{
		$plugin->register('Module::Field::date', $this, 'build');
		
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
		$var_name = ($field == '0')?"other":"data";
		$field = ($field == '0')?$custom_field:$field;

		if( $page == 'search' )
		{
			$_adj = at_array_val($setting, 'adj','EQ');
			if($_adj != 'EQ')
			{
				$field = $_adj.'_'.$field;
			}
			$control_name = $field;
		}
		else
		{
			$control_name = "{$var_name}[{$field}]";
		}

		$value = "";
		$style = "";

		if( at_array_val($setting, 'width') )
		{
			$style = 'style="width:'.$setting['width'].'px"';
		}
		if( $page == 'add' || $page == 'search')
		{
			if( $v = at_array_val($setting, 'value') )
			{
				$value = $v;
			}
		}
		else 
		{
			if( $v = at_array_val($setting, 'value') )
			{
				$value = '{at_array_val($'.$var_name.', \''.$field.'\', \''.$v.'\')}';
			}
		}


		$html_disabled = "<input type=\"text\" class=\"form-control\" name=\"{$control_name}\" value=\"".$value."\" ".$style."  disabled>";
		$html_writer = '<div class="datepicker" id="'.$field.'" '.$style.'>'.PHP_EOL;
		$html_writer .= '	<a href="javascript:;" class="datepicker-btn"></a>'.PHP_EOL;
		$html_writer .= '	<input type="text" class="form-control" name="'.$control_name.'" readonly="" value="'.$value.'">'.PHP_EOL;
		$html_writer .= '</div>'.PHP_EOL;


		switch ($status) {
			case 'readonly':
			case 'disabled':
				$html = $html_disabled;
				$script_file = $script = "";

				break;
			default:
				$html = $html_writer;
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

				$script = '$("#'.$field.'").datepicker('.$param.');';
				break;
		}
		
		return array($html, $script, $script_file, 'show');
	}
}

/* End of file Date.php */
/* Location: ./apps/gencode/plugins/Date.php */
