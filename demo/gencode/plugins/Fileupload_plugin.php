<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *	Plugin Name: 文件上传
 *	Plugin URI: 
 *	Description: 
 *	Version: 0.1
 *	Author: Huangwj
 *	Author Email: jove9834@126.com
*/

class Fileupload_plugin
{
	private $_CI;

	public function __construct(&$plugin)
	{
		$plugin->register('Module::Field::fileupload', $this, 'build');
		
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

		$custom_field = at_array_val($setting, 'custom_field');
		$var_name = ($field == '0')?"other":"data";
		$field = ($field == '0')?$custom_field:$field;

		$html = '<div class="att">'.PHP_EOL;
		$html .= '	<div class="attb">'.PHP_EOL;
		$html .= '		<span id="upload_btn"></span>'.PHP_EOL;
		if( $page == 'edit' )
		{
			$html .= '		<input type="hidden" id="'.$field.'" name="'.$var_name.'['.$field.']" value="{at_array_val($'.$var_name.', \''.$field.'\', \'\')}">'.PHP_EOL;
		}
		else
		{
			$html .= '		<input type="hidden" id="'.$field.'" name="'.$var_name.'['.$field.']">'.PHP_EOL;
		}
		
		$html .= '	</div>'.PHP_EOL;
		$html .= '	<div>'.PHP_EOL;
		$html .= '		<div class="attl" id="file_target">'.PHP_EOL;
		//判断页面类型
		if( $page == 'edit' )
		{
			$html .= '			{at_attach($data[\''.$field.'\'])}';
		}
		$html .= '		</div>'.PHP_EOL;
		$html .= '	</div>'.PHP_EOL;
		$html .= '</div>'.PHP_EOL;

		$script_file = '<script type="text/javascript" src="{base_url(\'assets/js/swfupload/swfupload.packaged.js\')}"></script>'.PHP_EOL;
		$script_file .= '<script type="text/javascript" src="{base_url(\'assets/js/swfupload/handlers.js\')}"></script>'.PHP_EOL;

		if( $setting['file_type'] == 'image')
		{
			$file_types = '*.gif;*.jpg;*.jpeg;*.png;';
			$file_types_description = "All Image Formats";
		}
		else 
		{
			$file_types = '*.*;';
			$file_types_description = "All Support Formats";
		}
		
		$script = '// 图片上传配置'.PHP_EOL;
		$script .= 'var picUpload = Ibos.upload.attach({'.PHP_EOL;
		$script .= '	upload_url: base_path + \'{at_url("attach/upload",FALSE,"")}\','.PHP_EOL;
        $script .= '	file_post_name : "Filedata",'.PHP_EOL;
        $script .= '	file_size_limit: 1000,'.PHP_EOL;
        $script .= '	file_upload_limit: 1,'.PHP_EOL;
        $script .= '	file_types: "'.$file_types.'",'.PHP_EOL;
        $script .= '	file_types_description: \''.$file_types_description.'\','.PHP_EOL;
        $script .= '	post_params: {module:\''.$module_class.'\',session:\'{at_authcode(LOGIN_UID,"ENCODE")}\'},'.PHP_EOL;
		$script .= '	button_placeholder_id: "upload_btn",'.PHP_EOL;
		$script .= '	custom_settings: {'.PHP_EOL;
		$script .= '		containerId: "file_target",'.PHP_EOL;
		$script .= '		inputId: "'.$field.'"'.PHP_EOL;
		$script .= '	}'.PHP_EOL;
		$script .= '});'.PHP_EOL;
		return array($html, $script, $script_file, 'show');
	}
}

/* End of file Fileupload.php */
/* Location: ./apps/gencode/plugins/Fileupload.php */

