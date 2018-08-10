<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *	Plugin Name: 隐藏域
 *	Plugin URI: 
 *	Description: 
 *	Version: 0.1
 *	Author: Huangwj
 *	Author Email: jove9834@126.com
*/

class Hidden_plugin
{
	private $_CI;

	public function __construct(&$plugin)
	{
		$plugin->register('Module::Field::hidden', $this, 'build');
		
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
		$custom_field = at_array_val($setting, 'custom_field');
		$status = at_array_val($setting, $page.'_status','');

		$var_name = ($field == '0')?"other":"data";
		$field = ($field == '0')?$custom_field:$field;
		if( $page == 'search' )
		{
			$control_name = $field;
		}
		else
		{
			$control_name = "{$var_name}[{$field}]";
		}

		$html = "<input type=\"hidden\" class=\"form-control\" name=\"{$control_name}\"";
		
		if( $page == 'add' || $page == 'search')
		{
			if( $v = at_array_val($setting, 'value') )
			{
				$html .= " value=\"".$v."\"";
			}
		}
		else 
		{
			$v = at_array_val($setting, 'value', '');
			$html .= ' value="{at_array_val($'.$var_name.', \''.$field.'\', \''.$v.'\')}"';
			
		}
		$html .= ">".PHP_EOL;
		return array($html, '', '', 'hidden');
	}
}

/* End of file Hidden_plugin.php */
/* Location: .//Users/huangwj/Sites/0034/apps/gencode/plugins/Hidden_plugin.php */
