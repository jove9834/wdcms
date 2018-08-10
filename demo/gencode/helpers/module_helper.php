<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 生成字段控件
 *array('name'=>'keyword', 'title'=>'关键字', 'type'=>'text','placeholder'=>'')
 * @return void
 * @author 
 **/
function md_input_field($params=array(), $is_edit=false)
{
	if(empty($params)) return FALSE;
	$type = 'text';
	if(!empty($params['type'])) $type = $params['type'];
	if(!empty($params['name'])) $name = $params['name'];
	$attrs = array_diff_key($params, array('type'=>''));

	$result = '<div class="title">'.$params['title'].'</div>';
	$result .= '<div class="input">';


	switch ($type) {
		case 'hidden':
			if($is_edit)
			{
				$attrs['value'] = '{$data[\''.$name.'\']}';
			}
			$result = '<input type="hidden" '.md_input_attr($attrs).'/>';
			break;
		case 'text':
			if($is_edit)
			{
				$attrs['value'] = '{$data[\''.$name.'\']}';
			}
			$result = '<input type="text" '.md_input_attr($attrs).'/>';
			break;
		case 'email':
			if($is_edit)
			{
				$attrs['value'] = '{$data[\''.$name.'\']}';
			}
			$result = '<input type="email" '.md_input_attr($attrs).'/>';
			break;
		case 'checkbox':
		case 'radio':
			$list = $params['list'];
			$attrs = array_diff_key($attrs, array('list'=>''));
			$list = explode(';', $list);
			$m = 1;
			foreach ($list as $value) {
				$item = explode(',', $value);
				$value = $title = $item[0];
				if(isset($item[1])) $value = $item[1];
				if(isset($item[2])) $id = $item[2];
				else $id=$name.'_'.$m;
				$result .= '<input type="'.$type.'" id="'.$id.'" '.md_input_attr($attrs).'>';
				$result .= '<label for="'.$id.'">'.$title.'</label>';
				$m++;
			}
			break;
		default:
			if($is_edit)
			{
				$attrs['value'] = '{$data[\''.$name.'\']}';
			}
			$result = '<input type="text" '.md_input_attr($attrs).'/>';
			break;
	}
	$result .= '</div>';
	return $result;

}

function md_input_attr($params)
{

	foreach ($params as $key => $value) 
	{
		$result = $key.'="'.$value.'" ';
	}
	return $result;
}

function md_build_view($view_config, $idx=1)
{
	$html = "";
	$setting = at_string2array($view_config['setting']);
    if( $setting['type'] == 'url')
    {
    	$html = "<li><a href=\"{at_url('".$setting['url']."')}\" title=\"{lang('view_".$idx."')}\">{lang('view_".$idx."')}</a></li>";
    }
    else if( $setting['type'] == 'array')
    {
		$html  = '{loop $'.$setting['array'].' $t $v}'.PHP_EOL;
		$html .= '{if !empty($v)}'.PHP_EOL;
		$html .= '<li><a href="{at_url(\''.$setting['url'].'\')}" title="{$v}">{$v}</a></li>'.PHP_EOL;
		$html .= '{/if}'.PHP_EOL;
		$html .= '{/loop}'.PHP_EOL;
    }
    else if( $setting['type'] == 'array2')
    {
    	$html  = '{loop $'.$setting['array'].' $t}'.PHP_EOL;
		$html .= '{if !empty($t)}'.PHP_EOL;
		$html .= '<li><a href="{at_url(\''.$setting['url'].'\')}" title="{$t[\'name\']}">{$t[\'name\']}</a></li>'.PHP_EOL;
		$html .= '{/if}'.PHP_EOL;
		$html .= '{/loop}'.PHP_EOL;
    }
    return $html;
}

function md_build_toolbtns($btns)
{
	$group_id = 0;
	$html = "";
	$idx = 1;
    foreach ($btns as $v) 
    {
        $html .= md_build_button($v, $group_id, $idx);
        $idx ++ ;
    }
    if( $group_id > 0 )
	{
		$html .= "	</ul>".PHP_EOL;
		$html .= "</div>".PHP_EOL;
	}
	return $html;
}

function md_build_button($btn_config, &$group_id, $idx=1)
{
	$html = "";
	$setting = at_string2array($btn_config['setting']);
	if( $group_id > 0 && $btn_config['pid']!= $group_id )
	{
		$html .= "	</ul>".PHP_EOL;
		$html .= "</div>".PHP_EOL;
		$group_id = 0;
	}
	//普通按钮
	if( $btn_config['is_group'] == 0 && $btn_config['pid'] == 0)
	{
		$html .='<a href="{at_url(\''.$setting['url'].'\')}" class="'.$setting['cssclass'].' "';
		if( at_array_val($setting,'dialog') )
		{
			$html .=' data-dismiss="dialog"';
		}
		if(  at_array_val($setting,'is_post'))
		{
			$html .=' data-post="true"';
		}
		$html .= " title=\"{lang('btn_".$idx."')}\">{lang('btn_".$idx."')}</a>".PHP_EOL;
		return $html;
	}
	
	if( at_array_val($btn_config,'is_group') )
	{
		//分组
		$html  = '<div class="btn-group" id="atb_more">'.PHP_EOL;
		$html .= '	<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">'.PHP_EOL;
		$html .= '		{lang(\'btn_'.$idx.'\')}<i class="caret"></i>'.PHP_EOL;
		$html .= '	</button>'.PHP_EOL;
		$html .= '	<ul class="dropdown-menu">'.PHP_EOL;	
		$group_id = $btn_config['id'];
		return $html;
	}

	if( $btn_config['pid'] > 0)
	{
		$html .='		<li>'.PHP_EOL;
		$html .='			<a href="{at_url(\''.$setting['url'].'\')}"';
		if( at_array_val($setting,'dialog') )
		{
			$html .=' data-dismiss="dialog"';
		}
		if( at_array_val($setting,'is_post') )
		{
			$html .=' data-post="true"';
		}
		$html .= " title=\"{lang('btn_".$idx."')}\">{lang('btn_".$idx."')}</a>".PHP_EOL;
		$html .= '		</li>'.PHP_EOL;
		return $html;
	}
	return $html;
}

function md_build_list_header($config, $idx=1)
{
	$html = "";
	$setting = at_string2array($config['setting']);
	$html .= "<th align=\"".at_array_val($setting,'align','left')."\"";
	if( $width = at_array_val($setting, 'width') )
	{
		$html .= " width=\"".$width."\"";
	}
	$html .= ">".PHP_EOL;
	if($setting['format'] == 'checkbox')
	{
		$html .= "<label class=\"checkbox\">".PHP_EOL;
		$html .= "	<input type=\"checkbox\" data-name=\"ids[]\">".PHP_EOL;
		$html .= "</label>".PHP_EOL;	
	}
	else 
	{
		//$html .= $setting['name'];	
		$html .= "{lang('list_".$setting['field']."')}";	
	}

	$html .= "</th>".PHP_EOL;
	return $html;
}

function md_build_list_cell($config)
{
	$html = "";
	$setting = at_string2array($config['setting']);
	$field = $setting['field'];

	$html .= "<td align=\"".at_array_val($setting,'align','left')."\">".PHP_EOL;
	if($setting['format'] == 'checkbox')
	{
		$html .= '<label class="checkbox">'.PHP_EOL;
		$html .= '	<input type="checkbox" name="ids[]" value="{$item[\'id\']}">'.PHP_EOL;
		$html .= "</label>".PHP_EOL;	
	}
	else if($setting['format'] == 'date')
	{
		$html .= '{at_format($item[\''.$field.'\'], \'date\')}';
	}
	else if($setting['format'] == 'time')
	{
		$html .= '{at_format($item[\''.$field.'\'], \'time\')}';
	}
	else if($setting['format'] == 'datetime')
	{
		$html .= '{at_format($item[\''.$field.'\'], \'datetime\')}';
	}
	else if($setting['format'] == 'input')
	{
		$html .= '<input type="text" class="form-control" name="data[{$item[\'id\']}]['.$field.']" value="{$item[\''.$field.'\']}"/>';	
	}
	else if($setting['format'] == 'a')
	{	//	超链接
		$html .= '<a href="{at_url(\''.$setting['url'].'\', array(\'id\'=>$item[\'id\']))}"';
		//
		if( at_array_val($setting,'dialog') )
		{
			$html .=' data-dismiss="dialog"';
		}
		if( at_array_val($setting,'is_post') )
		{
			$html .=' data-post="true"';
		}

		$html .= '>{$item[\''.$field.'\']}</a>';
	}
	else if($setting['format'] == 'state')
	{	//状态显示
		$html .= '{$item[\''.$field.'\']}';	
	}
	else 
	{
		$html .= '{$item[\''.$field.'\']}';	
	}

	$html .= PHP_EOL."</td>".PHP_EOL;
	return $html;
}

function md_build_field($setting, $page, $module_class)
{
	$CI =& get_instance();
	$type = $setting['type'];
	$field = $setting['field'];
	$hook_name = "Module::Field::";
	return $CI->plugin->trigger($hook_name.$type, $setting, $page, $module_class);
}

function md_parse_var($template)
{
	// 正则表达式匹配的模板标签
    $regex_array = array(
		// 3维数组变量
		'#{\$(\w+?)\.(\w+?)\.(\w+?)\.(\w+?)}#i',
		// 2维数组变量
		'#{\$(\w+?)\.(\w+?)\.(\w+?)}#i',
		// 1维数组变量
		'#{\$(\w+?)\.(\w+?)}#i',
		
		// 3维数组变量
		'#\$(\w+?)\.(\w+?)\.(\w+?)\.(\w+?)#Ui',
		// 2维数组变量
		'#\$(\w+?)\.(\w+?)\.(\w+?)#Ui',
		// 1维数组变量
		'#\$(\w+?)\.(\w+?)#Ui',
		
        // PHP函数
        '#{([a-z_0-9]+)\((.*)\)}#Ui',
        // PHP常量
        '#{([A-Z_]+)}#',
        //PHP变量格式化
        '#{\$(.+?)\s*format:(.+?)}#i',
        // PHP变量
        '#{\$(.+?)}#i'
    );

    // 替换直接变量输出
    $replace_array = array(		
        "\$\\1['\\2']['\\3']['\\4']",
        "\$\\1['\\2']['\\3']",
        "\$\\1['\\2']",
		
        "\$\\1['\\2']['\\3']['\\4']",
        "\$\\1['\\2']['\\3']",
        "\$\\1['\\2']",
		
        "\\1(\\2)",
        "\\1",
        "at_format(\$\\1, \"\\2\")",
        "\$\\1"
    );
	
	return preg_replace($regex_array, $replace_array, $template);	
}
/* End of file module_helper.php */
/* Location: .//Users/huangwj/Sites/e-web/apps/gencode/helpers/module_helper.php */