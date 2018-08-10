<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *	Plugin Name: 下拉选择框
 *	Plugin URI: 
 *	Description: 
 *	Version: 0.1
 *	Author: Huangwj
 *	Author Email: jove9834@126.com
*/

class Select_plugin
{
	private $_CI;

	public function __construct(&$plugin)
	{
		$plugin->register('Module::Field::select', $this, 'build');
		
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

		$html = "<select class=\"form-control\" name=\"{$control_name}\"";
		

		switch ($status) {
			case 'readonly':
			case 'disabled':
				$html .= " disabled";
				break;
			default:
				break;
		}

		if( at_array_val($setting, 'required') )
		{
			$html .=" data-dismiss=\"validate\" data-required=\"true\"";
		}

		//
		if( !empty($setting['width']) )
		{
			$html .=" style=\"width:".$setting['width']."px\"";
		}
		
		$html .= ">".PHP_EOL;
		
		$value = at_array_val($setting, 'value');

		//下拉框值
		$options = $setting['lists'];
		$options = explode(PHP_EOL, str_replace(array(chr(13), chr(10)), PHP_EOL, $options));
		foreach ($options as $t) {
			if ($t) {
				$n = $v = $selected = '';
				list($n, $v) = explode('|', $t);
				$v = is_null($v) ? trim($n) : trim($v);
				if( $page == 'add' || $page == 'search')
				{
					$selected = $v==$value ? ' selected' : '';
				}
				else 
				{
					$selected = '{if at_array_val($'.$var_name.',\''.$field.'\')==\''.$v.'\'}selected{/if}';	
				}
				
				$html.= '<option value="'.$v.'" ' . $selected . '>'.$n.'</option>'.PHP_EOL;
			}
		}

		$html .="</select>";
		return array($html, '', '', 'show');
	}
}

/* End of file Select.php */
/* Location: ./apps/gencode/plugins/Select.php */

