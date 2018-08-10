<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('gencode',SITE_LANGUAGE);
		$this->load->model('module_model');
		$this->load->model('module_config_model');
		$this->load->helper('module');
	}

	public function index()
	{	
		list($params, $page, $total) = $this->_params();
		//分页
		$this->module_model->default_order_by('id', 'desc');
		list($modules, $total) = $this->module_model->limit_page($page, $total, $params);
		$params['total'] = $total;
		$this->template->assign(array(
			'lists'=> $modules,
			'pages'=>$this->_pagination(at_url('module',$params),$total)
		));
		$this->template->set_decorators('admin/common/layout.html','');
		$this->template->display('module_list.html');	
	}

	public function module_new()
	{
		list($params) = $this->_params();
		if(IS_POST && IS_AJAX)
		{
			$data = $params['data'];
			//提交
			if( isset($params['func']) )
			{
				$data['func'] = implode(',', $params['func']);
			}
			$id = $this->module_model->add($data);
			if( $id !== FALSE )
			{
				//成功
				$this->_ajax_request_return(1, $this->module_model->messages(), $id);
			}
			else 
			{
				//出错
				$this->_ajax_request_return(0, $this->module_model->errors());
			}
		}
		//表名称列表
		$tables = $this->db->list_tables();

		$this->template->assign('tables', $tables);

		$this->template->display('module_new.html');	
	}

	public function module_edit()
	{
		list($params) = $this->_params();
		if(IS_POST && IS_AJAX)
		{
			//提交
			$data = $params['data'];
			if( isset($params['func']) )
			{
				$data['func'] = implode(',', $params['func']);
			}
			$result = $this->module_model->update($params['id'], $data);
			if( $result !== FALSE )
			{
				//成功
				$this->_ajax_request_return(1, $this->module_model->messages());
			}
			else 
			{
				//出错
				$this->_ajax_request_return(0, $this->module_model->errors());
			}
		}

		//表名称列表
		$tables = $this->db->list_tables();
		$module = $this->module_model->get($params['id'])->row_array();
		$this->module_model->free_result();

		$this->template->assign('module', $module);
		$this->template->assign('tables', $tables);
		$this->template->display('module_edit.html');	
	}

	public function module_view()
	{
		list($params) = $this->_params();
		$module = $this->module_model->get($params['id'])->row_array();
		$this->module_model->free_result();

		$this->template->assign('module', $module);
		$this->template->set_decorators('admin/common/layout.html','');
		$this->template->display('module_view.html');	
	}

	

	/**
	 * 删除操作
	 *
	 * @return void
	 * @author 
	 **/
	public function module_del(){
		$id = $this->input->get('id');
		if( $this->module_model->delete($id) )
		{
			$this->_ajax_request_return(1, $this->module_model->messages());
		}
		else 
		{
			$this->_ajax_request_return(0, $this->module_model->errors());	
		}
	}

	public function module_del_all(){
		$ids = $this->input->post('ids');
		if( empty($ids) )
		{
			$this->_ajax_request_return(0, lang('no_selected'));	
		}
		$sels = explode(',', $ids);
		$succ = 0;
		$fail = 0;
		foreach ($sels as $value) {
			if( $this->module_model->delete($value) )
			{
				$succ ++;
			}	
			else 
			{
				$fail ++;
			}
		}
		$this->_ajax_request_return(1, at_lang('delete_all_tip', count($sels), $succ, $fail));
	}

	private function parse($output, $save_file_name)
	{
		//替换
		$output = str_replace('<%php', '<?php', $output);
		$output = str_replace('%>', '?>', $output);
		at_mkdirs(dirname($save_file_name));
		//保存
		if ( ! $fp = @fopen($save_file_name, FOPEN_WRITE_CREATE_DESTRUCTIVE))
		{
			log_message('error', "Unable to write file: ".$save_file_name);
			return;
		}
		if (flock($fp, LOCK_EX))
		{
			fwrite($fp, $output);
			flock($fp, LOCK_UN);
		}
		else
		{
			log_message('error', "Unable to secure a file lock for file at: ".$save_file_name);
			return;
		}
		fclose($fp);

		//echo $output;
	}

	public function gen_all()
	{
		list($params) = $this->_params();
		$module_id = $params['module_id'];
		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();
		if (at_exists_strlist('list',$module['func']))
		{
			$this->gen_list();
			echo "<br>";
		}
		if (at_exists_strlist('new',$module['func']))
		{
			$this->gen_add();
			echo "<br>";
			$this->gen_edit();
			echo "<br>";
		}
		if (at_exists_strlist('search',$module['func']))
		{
			$this->gen_search();
			echo "<br>";
		}
		$this->gen_controller();
		echo "<br>";
		$this->gen_model();
		echo "<br>";
		
		$this->gen_lang();
		//更新状态
		$this->module_model->update($module_id, array('status'=>1));
	}

	public function move_to_product_env()
	{
		list($params) = $this->_params();
		$module_id = $params['module_id'];
		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();

		$class_name = $module['class_name'];
		$app = at_array_val($module, 'app', '');
		$files = array(
			at_app_path('templates/default/'.$class_name.'/'.$class_name.'_list.html', 'temp')=>at_app_path('templates/default/'.$class_name.'/'.$class_name.'_list.html', $app),
			at_app_path('templates/default/'.$class_name.'/'.$class_name.'_add.html', 'temp')=>at_app_path('templates/default/'.$class_name.'/'.$class_name.'_add.html', $app),
			at_app_path('templates/default/'.$class_name.'/'.$class_name.'_edit.html', 'temp')=>at_app_path('templates/default/'.$class_name.'/'.$class_name.'_edit.html', $app),
			at_app_path('templates/default/'.$class_name.'/'.$class_name.'_search.html', 'temp')=>at_app_path('templates/default/'.$class_name.'/'.$class_name.'_search.html', $app),
			at_app_path('templates/default/'.$class_name.'/'.$class_name.'_view.html', 'temp')=>at_app_path('templates/default/'.$class_name.'/'.$class_name.'_view.html', $app),
			at_app_path('controllers/admin/'.$class_name.'.php', 'temp')=>at_app_path('controllers/admin/'.$class_name.'.php', $app),
			at_app_path('models/'.$class_name.'_model.php', 'temp')=>at_app_path('models/'.$class_name.'_model.php', $app),
			at_app_path('language/zh-cn/'.$class_name.'_lang.php', 'temp')=>at_app_path('language/zh-cn/'.$class_name.'_lang.php', $app)
		);
		$out = "";
		foreach ($files as $key => $value) 
		{
			if(file_exists($key))
			{
				at_mkdirs(dirname($value));
				copy($key, $value);
				$out .= "原文件{$key}复制到{$value}<br/>";
			}
		}
		echo $out;
	}

	public function gen_list($module_id=FALSE)
	{
		list($params) = $this->_params();
		if( !$module_id ) $module_id = $params['module_id'];
		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();
		//取列表基础配置信息
		$base_config = $this->module_config_model->where(array('module_id'=>$module_id, 'page'=>'list', 'type'=>'base'))->get_data()->row_array();
		if( empty($base_config) )
		{
			$base_config = array();
		}
		//取视图配置信息
		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'list',
												'type'=>'view'))
								  ->order_by('display_order');
		$views = $this->module_config_model->get_data()->result_array();
		$this->module_config_model->free_result();



		//取按钮
		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'list',
												'type'=>'toolbar'))
								  ->order_by('display_order');

		$btns = $this->module_config_model->get_data()->result_array();

		$this->module_config_model->free_result();

		$btns = at_sort($btns);

		//取列表
		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'list',
												'type'=>'list'))
								  ->order_by('display_order');

		$lists = $this->module_config_model->get_data()->result_array();

		$this->module_config_model->free_result();

		$base_setting = at_string2array($base_config['setting']);


		//取编辑页面配置信息
		$edit_config = $this->module_config_model->where(array('module_id'=>$module_id, 'page'=>'add', 'type'=>'base'))->get_data()->row_array();
		if( empty($edit_config) )
		{
			$edit_dialog = false;
		}
		else 
		{
			$edit_setting = at_string2array($edit_config['setting']);	
			$edit_dialog = at_array_val($edit_setting, 'dialog', FALSE);
		}
		

		$template_file = at_array_val($base_setting, 'template', 'list-1.php');
		$module_class_name = $module['class_name'];
		
		ob_start();
		include(at_app_path('config/templates/'.$template_file));
		$output = ob_get_contents();
		@ob_end_clean();
		//$app = at_array_val($module, 'app', 'temp');
		$app = 'temp';
		
		$save_file_name = at_app_path('templates/default/'.$module_class_name.'/'.$module_class_name.'_list.html', $app);
		$this->parse($output, $save_file_name);
		//@ob_end_clean();
		echo $save_file_name.'生成成功';
	}

	public function gen_controller($module_id=FALSE)
	{
		//生成控制类
		list($params) = $this->_params();
		if( !$module_id ) $module_id = $params['module_id'];
		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();

		//取列表基础配置信息
		$base_config = $this->module_config_model->where(array('module_id'=>$module_id, 'page'=>'list', 'type'=>'base'))->get_data()->row_array();
		if( empty($base_config) )
		{
			$list_setting = array();
		}
		else 
		{
			$list_setting = at_string2array($base_config['setting']);	
		}
		
		$keyword = at_array_val($list_setting, 'keyword', FALSE);  
		$search = at_array_val($list_setting, 'search', FALSE);  
		$keyword_field_str = at_array_val($list_setting, 'keyword_field', '');
		if($keyword && $keyword_field_str != '')
		{
			$keyword_field = '';
			$arr = explode(',', $keyword_field_str);
			foreach ($arr as $v) {
				$keyword_field = trim($keyword_field,',').",'".$v."'";
			}
			$keyword_field = trim($keyword_field,',');
		}
		$page = at_array_val($list_setting, 'page', FALSE);

		//取编辑页面配置信息
		$edit_config = $this->module_config_model->where(array('module_id'=>$module_id, 'page'=>'add', 'type'=>'base'))->get_data()->row_array();
		if( empty($edit_config) )
		{
			$edit_dialog = false;
		}
		else 
		{
			$edit_setting = at_string2array($edit_config['setting']);	
			$edit_dialog = at_array_val($edit_setting, 'dialog', FALSE);
		}
		
		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'controller',
												'type'=>'value'))
								  ->order_by('display_order');

		$defaultValues = $this->module_config_model->get_data()->result_array();
		$this->module_config_model->free_result();


		$template_file = 'controller.php';
		$module_class_name = $module['class_name'];
		$table_name = $module['table_name'];

		ob_start();
		include(at_app_path('config/templates/'.$template_file));
		$output = ob_get_contents();
		@ob_end_clean();
		//$app = at_array_val($module, 'app', 'temp');
		$app = 'temp';

		$save_file_name = at_app_path('controllers/admin/'.$module_class_name.'.php', $app);
		$this->parse($output, $save_file_name);
		//@ob_end_clean();
		echo $save_file_name.'生成成功';
	}

	public function gen_model($module_id=FALSE)
	{
		//生成控制类
		list($params) = $this->_params();
		if( !$module_id ) $module_id = $params['module_id'];
		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();
		
		$template_file = 'model.php';
		$module_class_name = $module['class_name'];
		$table_name = $module['table_name'];


		ob_start();
		include(at_app_path('config/templates/'.$template_file));
		$output = ob_get_contents();
		@ob_end_clean();
		//$app = at_array_val($module, 'app', 'temp');
		$app = 'temp';
		
		$save_file_name = at_app_path('models/'.$module_class_name.'_model.php', $app);
		$this->parse($output, $save_file_name);
		//@ob_end_clean();
		echo $save_file_name.'生成成功';
	}	

	public function gen_add($module_id=FALSE)
	{
		list($params) = $this->_params();
		if( !$module_id ) $module_id = $params['module_id'];
		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();
		//取基础配置信息
		$base_config = $this->module_config_model->where(array('module_id'=>$module_id, 'page'=>'add', 'type'=>'base'))->get_data()->row_array();
		if( empty($base_config) )
		{
			$base_config = array();
		}
		
		//取字段配置信息
		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'add',
												'type'=>'field'))
								  ->order_by('display_order');
		$fields = $this->module_config_model->get_data()->result_array();
		$this->module_config_model->free_result();

		
		$base_setting = at_string2array($base_config['setting']);
		$edit_dialog = at_array_val($base_setting, 'dialog', FALSE);

		$template_file = at_array_val($base_setting, 'add_template', 'add-2.php');

		$module_class_name = $module['class_name'];
		
		//加载插件
		$plugin_dir = at_app_path('plugins/');
		$plugin_dir_map = directory_map($plugin_dir,1);
		foreach ($plugin_dir_map as $file) {
			$path = $plugin_dir.$file;
			$plugin_name = substr( $file, 0, -4);
			log_message('debug','path:'.$path);
			log_message('debug','plugin_name:'.$plugin_name);

			$this->plugin->add_plugin($plugin_name, $path);
		}

		ob_start();
		include(at_app_path('config/templates/'.$template_file));
		$output = ob_get_contents();
		@ob_end_clean();
		//$app = at_array_val($module, 'app', 'temp');
		$app = 'temp';
		
		$save_file_name = at_app_path('templates/default/'.$module_class_name.'/'.$module_class_name.'_add.html', $app);
		$this->parse($output, $save_file_name);
		//@ob_end_clean();
		echo $save_file_name.'生成成功';
	}	

	public function gen_edit($module_id=FALSE)
	{
		list($params) = $this->_params();
		if( !$module_id ) $module_id = $params['module_id'];
		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();
		//取基础配置信息
		$base_config = $this->module_config_model->where(array('module_id'=>$module_id, 'page'=>'add', 'type'=>'base'))->get_data()->row_array();
		if( empty($base_config) )
		{
			$base_config = array();
		}
		//取字段配置信息
		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'add',
												'type'=>'field'))
								  ->order_by('display_order');
		$fields = $this->module_config_model->get_data()->result_array();
		$this->module_config_model->free_result();

		
		$base_setting = at_string2array($base_config['setting']);
		$edit_dialog = at_array_val($base_setting, 'dialog', FALSE);


		$template_file = at_array_val($base_setting, 'edit_template', 'edit-2.php');

		$module_class_name = $module['class_name'];
		

		//加载插件
		$plugin_dir = at_app_path('plugins/');
		$plugin_dir_map = directory_map($plugin_dir,1);
		foreach ($plugin_dir_map as $file) {
			$path = $plugin_dir.$file;
			$plugin_name = substr( $file, 0, -4);
			log_message('debug','path:'.$path);
			log_message('debug','plugin_name:'.$plugin_name);

			$this->plugin->add_plugin($plugin_name, $path);
		}
		ob_start();
		include(at_app_path('config/templates/'.$template_file));
		$output = ob_get_contents();
		@ob_end_clean();
		//$app = at_array_val($module, 'app', 'temp');
		$app = 'temp';
		$save_file_name = at_app_path('templates/default/'.$module_class_name.'/'.$module_class_name.'_edit.html', $app);
		$this->parse($output, $save_file_name);
		//@ob_end_clean();
		echo $save_file_name.'生成成功';
	}	

	public function gen_search($module_id=FALSE)
	{
		list($params) = $this->_params();
		if( !$module_id ) $module_id = $params['module_id'];
		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();
		//取基础配置信息
		$base_config = $this->module_config_model->where(array('module_id'=>$module_id, 'page'=>'search', 'type'=>'base'))->get_data()->row_array();
		if( empty($base_config) )
		{
			$base_config = array();
		}
		//取字段配置信息
		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'search',
												'type'=>'field'))
								  ->order_by('display_order');
		$fields = $this->module_config_model->get_data()->result_array();
		$this->module_config_model->free_result();

		
		$base_setting = at_string2array($base_config['setting']);

		$template_file = at_array_val($base_setting, 'template', 'search.php');

		$module_class_name = $module['class_name'];
		
		//加载插件
		$plugin_dir = at_app_path('plugins/');
		$plugin_dir_map = directory_map($plugin_dir,1);
		foreach ($plugin_dir_map as $file) {
			$path = $plugin_dir.$file;
			$plugin_name = substr( $file, 0, -4);
			log_message('debug','path:'.$path);
			log_message('debug','plugin_name:'.$plugin_name);

			$this->plugin->add_plugin($plugin_name, $path);
		}

		ob_start();
		include(at_app_path('config/templates/'.$template_file));
		$output = ob_get_contents();
		@ob_end_clean();
		//$app = at_array_val($module, 'app', 'temp');
		$app = 'temp';
		
		$save_file_name = at_app_path('templates/default/'.$module_class_name.'/'.$module_class_name.'_search.html', $app);
		$this->parse($output, $save_file_name);
		//@ob_end_clean();
		echo $save_file_name.'生成成功';
	}

	public function gen_lang($module_id=FALSE)
	{
		$gen_lang = array();
		list($params) = $this->_params();
		if( !$module_id ) $module_id = $params['module_id'];
		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();
		//取列表基础配置信息
		$base_config = $this->module_config_model->where(array('module_id'=>$module_id, 'page'=>'list', 'type'=>'base'))->get_data()->row_array();
		if( empty($base_config) )
		{
			$base_config = array();
		}

		//取视图配置信息
		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'list',
												'type'=>'view'))
								  ->order_by('display_order');
		$views = $this->module_config_model->get_data()->result_array();
		$this->module_config_model->free_result();



		//取按钮
		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'list',
												'type'=>'toolbar'))
								  ->order_by('display_order');

		$btns = $this->module_config_model->get_data()->result_array();

		$this->module_config_model->free_result();

		$btns = at_sort($btns);

		//取列表
		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'list',
												'type'=>'list'))
								  ->order_by('display_order');

		$lists = $this->module_config_model->get_data()->result_array();

		$this->module_config_model->free_result();

		$base_setting = at_string2array($base_config['setting']);


		//取字段配置信息
		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'add',
												'type'=>'field'))
								  ->order_by('display_order');
		$fields = $this->module_config_model->get_data()->result_array();
		$this->module_config_model->free_result();

		
		$gen_lang[$module['class_name']] = $module['name'];
		$gen_lang['page_title'] = at_array_val($base_setting, 'title');
		if (at_array_val($base_setting,'keyword'))
		{
			$gen_lang['keyword_placeholder'] = at_array_val($base_setting, 'keyword_p');
		}	
		
		//取列表各字段显示值
		foreach ($lists as $item) {
			$setting = at_string2array($item['setting']);
			$gen_lang['list_'.$setting['field']] = at_array_val($setting, 'name');
		}
		//视图
		$idx = 1;
		foreach ($views as $item) {
			$setting = at_string2array($item['setting']);
			$gen_lang['view_'.$idx] = at_array_val($setting, 'name');
			$idx ++;
		}
		//按钮
		$idx = 1;
		foreach ($btns as $item) {
			$setting = at_string2array($item['setting']);
			$gen_lang['btn_'.$idx] = at_array_val($setting, 'name');
			$idx ++;
		}
		//取表单各字段显示值
		foreach ($fields as $item) {
			$setting = at_string2array($item['setting']);
			$gen_lang[$setting['field']] = at_array_val($setting, 'name');
			if( $tips =  at_array_val($setting, 'tips') )
			{
				$gen_lang['tip_'.$setting['field']] = $tips;	
			}
			if( $placeholder=at_array_val($setting, 'placeholder') )
			{
				$gen_lang['ph_'.$setting['field']] = $placeholder;
			}
		}

		//取查询字段配置信息
		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'search',
												'type'=>'field'))
								  ->order_by('display_order');
		$search_fields = $this->module_config_model->get_data()->result_array();
		$this->module_config_model->free_result();

		foreach ($search_fields as $item) {
			$setting = at_string2array($item['setting']);
			if( !at_array_val($gen_lang, $setting['field']) )
			{
				$gen_lang[$setting['field']] = at_array_val($setting, 'name');
			}
		}

		$gen_lang['edit_go_back'] = '返回';
		//模型操作提醒
		$table_name = $module['table_name'];
		$name = $module['name'];
		// $gen_lang[$table_name.'_name_required'] = '名称不能为空.';
		// $gen_lang[$table_name.'_already_exists'] = $name.'已存在.';
		// $gen_lang[$table_name.'_add_successful'] = $name.'添加成功.';

		// //update
		// $gen_lang[$table_name.'_id_required'] = $name.'ID不能为空.';
		// $gen_lang[$table_name.'_update_successful'] = $name.'更新成功.';

		// //delete
		// $gen_lang[$table_name.'_delete_unsuccessful'] = $name.'删除不成功';
		// $gen_lang[$table_name.'_delete_successful'] = $name.'删除成功';

		$module_class_name = $module['class_name'];
		ob_start();
		$template_file = "lang.php";
		include(at_app_path('config/templates/'.$template_file));
		$output = ob_get_contents();
		@ob_end_clean();
		$app = 'temp';

		$save_file_name = at_app_path('language/zh-cn/'.$module_class_name.'_lang.php', $app);
		$this->parse($output, $save_file_name);
		//@ob_end_clean();
		echo $save_file_name.'生成成功';
	}


}

/* End of file module.php */
/* Location: .//Users/huangwj/Sites/e-web/apps/gencode/controllers/module.php */