<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *	Plugin Name: 复选框
 *	Plugin URI: 
 *	Description: 
 *	Version: 0.1
 *	Author: Huangwj
 *	Author Email: jove9834@126.com
*/

class Radio_plugin
{
	private $_CI;

	public function __construct(&$plugin)
	{
		$plugin->register('Module::Field::radio', $this, 'build');
		
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
		
		$value = at_array_val($setting, 'value');

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
		$html = "";
		//下拉框值
		$options = $setting['lists'];
		$options = explode(PHP_EOL, str_replace(array(chr(13), chr(10)), PHP_EOL, $options));
		foreach ($options as $t) {
			if ($t) {
				$n = $v = $selected = '';
				list($n, $v) = explode('|', $t);
				$v = is_null($v) ? trim($n) : trim($v);
				
				$html .= "<label class=\"rc-label\">".PHP_EOL;
				$html .= "	<input type=\"radio\" name=\"{$control_name}\"";
				
				switch ($status) {
					case 'readonly':
					case 'disabled':
						$html .= " disabled";
						break;
					default:
						break;
				}
				if( $page == 'add' || $page == 'search')
				{
					$selected = $v==$value ? ' checked' : '';
				}
				else 
				{
					$selected = '{if at_array_val($'.$var_name.',\''.$field.'\')==\''.$v.'\'}checked{/if}';	
				}	
				$html .= " value=\"".$v."\" ".$selected;
				$html .= ">";
				$html .= $n;
				$html .= "</label>".PHP_EOL;
			}
		}
		return array($html, '', '', 'show');
	}
}
/* End of file Checkbox.php */
/* Location: ./apps/gencode/plugins/Checkbox.php */