<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *	Plugin Name: 颜色选择框
 *	Plugin URI: 
 *	Description: 
 *	Version: 0.1
 *	Author: Huangwj
 *	Author Email: jove9834@126.com
*/

class Color_plugin
{
	private $_CI;

	public function __construct(&$plugin)
	{
		$plugin->register('Module::Field::color', $this, 'build');
		
		$this->_CI = &get_instance();
	}

	public function build($setting, $page)
	{
		$field = $setting['field'];
		$status = $setting[$page.'_status'];

		$custom_field = at_array_val($setting, 'custom_field');
		$var_name = ($field == '0')?"other":"data";
		$field = ($field == '0')?$custom_field:$field;

		$html = "<input type=\"text\" class=\"form-control\" name=\"{$var_name}[{$field}]\" id=\"".$field."\"";
		switch ($status) {
			case 'readonly':
				$html .= " readonly";
				break;
			case 'disabled':
				$html .= " disabled";
				break;
			default:
				break;
		}
		$validate = false;

		if( at_array_val($setting, 'required') )
		{
			$html .=" data-required=\"true\"";
			$validate = true;
		}

		if( $setting['validate'] != 'none')
		{
			$validate = true;
			$valid_param = $setting['valid_param'];
			if( $setting['validate'] == 'other' )
			{
				//正则表达式验证
				$html .=" data-pattern=\"".$valid_param."\"";
			}
			else if( at_exists_strlist($setting['validate'],'minlength,maxlength,rangelength,min,max,range,remote') )
			{
				$html .=" data-".$setting['validate']."=\"".$valid_param."\"";
			}
			else
			{
				$html .=" data-".$setting['validate']."=\"true\"";
			}
		}		
		if( $validate ) $html .=" data-dismiss=\"validate\"";
		//
		if( !empty($setting['width']) )
		{
			$html .=" style=\"width:".$setting['width']."px\"";
		}
		if( $page == 'add')
		{
			if( $v = at_array_val($setting, 'value') )
			{
				$html .= " value=\"".$v."\"";
			}
		}
		else 
		{
			if( $v = at_array_val($setting, 'value') )
			{
				$html .= ' value="{at_array_val($'.$var_name.', \''.$field.'\', \''.$v.'\')}"';
			}
		}
		$html .= ">";

		$script = "$('#".$field."').colorpicker({".PHP_EOL;
        $script .= "		fillcolor:true".PHP_EOL;
    	$script .= "});".PHP_EOL;

		$script_file = '<script type="text/javascript" src="{base_url(\'assets/js/jquery.colorpicker.js\')}"></script>';

		return array($html, $script, $script_file, 'show');
	}
}

/* End of file Color.php */
/* Location: ./apps/gencode/plugins/Color.php */

