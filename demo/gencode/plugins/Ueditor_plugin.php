<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *	Plugin Name: Ueditor Html编辑框
 *	Plugin URI: 
 *	Description: 
 *	Version: 0.1
 *	Author: Huangwj
 *	Author Email: jove9834@126.com
*/

class Ueditor_plugin
{
	private $_CI;

	public function __construct(&$plugin)
	{
		$plugin->register('Module::Field::ueditor', $this, 'build');
		
		$this->_CI = &get_instance();
	}

	/**
	*
	*
	*
	*/
	public function build($setting, $page, $module_class)
	{
		$field = $setting['field'];
		$status = $setting[$page.'_status'];
		$html = "<textarea id=\"{$field}\" name=\"data[{$field}]\"";
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

		//
		$option = "{";
		if( !empty($setting['width']) )
		{
			$option .="width:'".$setting['width']."',";
		}
		if( !empty($setting['height']) )
		{
			$option .="height:'".$setting['height']."',";
		}

		$option = rtrim($option, ',')."}";
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
			$v = at_array_val($setting, 'value','');
			$html .= '{at_array_val($data, \''.$field.'\', \''.$v.'\')}';
		}
		$html .= "</textarea>".PHP_EOL;
		$script = "$.at.htmleditor('{$field}', '{site_url()}', 0, '{$module_class}', {$option});".PHP_EOL;

		$script_file = '<script type="text/javascript" src="{base_url(\'assets/js/ueditor/ueditor.config.js\')}"></script>'.PHP_EOL;
		$script_file .= '<script type="text/javascript" src="{base_url(\'assets/js/ueditor/ueditor.all.js\')}"></script>'.PHP_EOL;
		$script_file .= '<script type="text/javascript" src="{base_url(\'assets/js/ueditor/lang/zh-cn/zh-cn.js\')}"></script>'.PHP_EOL;
		$script_file .= '<script type="text/javascript" src="{base_url(\'assets/js/htmleditor.js\')}"></script>'.PHP_EOL;
		return array($html, $script, $script_file, 'show');
	}
}

/* End of file Htmleditor.php */
/* Location: ./apps/gencode/plugins/Htmleditor.php */
