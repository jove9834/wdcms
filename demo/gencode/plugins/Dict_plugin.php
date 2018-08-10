<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *	Plugin Name: 下拉选择框
 *	Plugin URI: 
 *	Description: 
 *	Version: 0.1
 *	Author: Huangwj
 *	Author Email: jove9834@126.com
*/

class Dict_plugin
{
	private $_CI;

	public function __construct(&$plugin)
	{
		$plugin->register('Module::Field::dict', $this, 'build');
		
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
		$dict_type = $setting['dict_type'];
		$dict_id = 'dict_'.$field;
		$html .= '{list id="'.$dict_id.'" class="tags.dict" method="options" app="" category="'.$dict_type.'"}'.PHP_EOL;
		if( $page == 'add' || $page == 'search')
		{
			if( $value )
			{
				$selected = '{if at_array_val($'.$var_name.',\''.$field.'\')==\''.$value.'\'}selected{/if}';
			}
			else 
			{
				$selected = "";
			}
		}
		else 
		{
			$selected = '{if at_array_val($'.$var_name.',\''.$field.'\')==$'.$dict_id.'[\'name\']}selected{/if}';	
		}
	    $html .= '<option value="{$'.$dict_id.'[\'name\']}" '.$selected.'>{$'.$dict_id.'[\'title\']}</option>'.PHP_EOL;
	    $html .= '{/list}'.PHP_EOL;

		$html .="</select>".PHP_EOL;
		return array($html, '', '', 'show');
	}
}

/* End of file Dict_plugin.php */
/* Location: ./apps/gencode/plugins/Dict_plugin.php */

