<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *	Plugin Name: 文本输入框
 *	Plugin URI: 
 *	Description: 
 *	Version: 0.1
 *	Author: Huangwj
 *	Author Email: jove9834@126.com
*/

class Text_plugin
{
	private $_CI;

	public function __construct(&$plugin)
	{
		$plugin->register('Module::Field::text', $this, 'build');
		
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

		$html = "<input type=\"text\" class=\"form-control\" name=\"{$control_name}\"";
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
		if( $page != 'search')
		{
			$validate = false;

			if( $maxlength=at_array_val($setting, 'maxlength') )
			{
				$html .=" maxlength=\"".$maxlength."\"";
			}

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
		}
		if( $placeholder=at_array_val($setting, 'placeholder') )
		{
			$html .=' placeholder="{lang(\'ph_'.$field.'\')}"';
		}
		//
		if( !empty($setting['width']) )
		{
			$html .=" style=\"width:".$setting['width']."px\"";
		}
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
		$html .= ">";
		return array($html, '', '', 'show');
	}
}

/* End of file Text.php */
/* Location: .//Users/huangwj/Sites/0034/apps/gencode/plugins/Text.php */
