<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *	Plugin Name: Html编辑框
 *	Plugin URI: 
 *	Description: 
 *	Version: 0.1
 *	Author: Huangwj
 *	Author Email: jove9834@126.com
*/

class Htmleditor_plugin
{
	private $_CI;

	public function __construct(&$plugin)
	{
		$plugin->register('Module::Field::htmleditor', $this, 'build');
		
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
		$status = $setting[$page.'_status'];
		$html = "<textarea class=\"form-control\" id=\"{$field}\" name=\"data[{$field}]\"";
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
		$style = "";
		if( !empty($setting['width']) )
		{
			$style .="width:".$setting['width']."px;";
		}
		else {
			$style .="width:600px;";	
		}
		if( !empty($setting['height']) )
		{
			$style .="height:".$setting['height']."px;";
		}
		else {
			$style .="height:200px;";	
		}
		if( !empty($style) )
		{
			$html .= " style=\"".$style."\"";
		}
		$html .= ">";
		if( $page == 'add')
		{
			if( $v = at_array_val($setting, 'value') )
			{
				$html .= $v;
			}
		}
		else 
		{
			$v = at_array_val($setting, 'value', '');
			$html .= '{at_array_val($data, \''.$field.'\', \''.$v.'\')}';
			
		}
		$html .= "</textarea>".PHP_EOL;
		$script = "var editor = KindEditor.create('#{$field}');".PHP_EOL;
		$script_file = '<link rel="stylesheet" href="{base_url(\'assets/js/kindeditor/themes/default/default.css\')}"/>'.PHP_EOL;
		$script_file .= '<script type="text/javascript" src="{base_url(\'assets/js/kindeditor/kindeditor-min.js\')}"></script>'.PHP_EOL;
		$script_file .= '<script type="text/javascript" src="{base_url(\'assets/js/kindeditor/lang/zh_CN.js\')}"></script>'.PHP_EOL;

		return array($html, $script, $script_file, 'show');
	}
}

/* End of file Htmleditor.php */
/* Location: ./apps/gencode/plugins/Htmleditor.php */
