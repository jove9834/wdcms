<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_page extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('gencode',SITE_LANGUAGE);
		$this->load->model('module_model');
		$this->load->model('module_config_model');

	}


	public function index()
	{
		list($params) = $this->_params();
		$module_id = $params['module_id'];
		$this->module_config_model->where('module_id', $module_id);
		$this->module_config_model->where('page', 'list');
		$this->module_config_model->where('type', 'view');
		$this->module_config_model->order_by('display_order');
		$views = $this->module_config_model->get_data()->result_array();
		$this->module_config_model->free_result();

		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'list',
												'type'=>'toolbar'))
								  ->order_by('display_order');

		$toolbars = $this->module_config_model->get_data()->result_array();
		$this->module_config_model->free_result();

		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'list',
												'type'=>'list'))
								  ->order_by('display_order');

		$list = $this->module_config_model->get_data()->result_array();

		$this->module_config_model->free_result();

		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();

		$this->template->assign('module', $module);

		$base_config = $this->module_config_model->where(array('module_id'=>$module_id, 'page'=>'list', 'type'=>'base'))->get_data()->row_array();
		if( empty($base_config) )
		{
			$base_data = array();
		}
		else 
		{
			$base_data = at_string2array($base_config['setting']);
			$base_data['display_order'] = $base_config['display_order'];
		}

		$this->template->assign(array('module_id'=>$module_id,
									  'views'=>$views,
									  'base' => $base_data,
									  'toolbars'=>at_sort($toolbars),
									  'list'=>$list));
		$this->template->set_decorators('admin/common/layout.html','');
		$this->template->display('list_page.html');	
	}

	public function add()
	{
		list($params) = $this->_params();
		$module_id = $params['module_id'];
		//提交处理
		if(IS_POST && IS_AJAX)
		{
			$setting = &$params['setting'];
			if( $setting['field'] == '0')
			{
				$setting['field'] = $params['custom_field'];
			}
			$this->_add('list',$params);
		}

		if($id=at_array_val($params, 'id'))
		{
			$config = $this->module_config_model->get($id)->row_array();
			$setting = at_string2array($config['setting']);
		}
		else 
		{
			$config = $setting = array();
		}
		$this->template->assign('data', $config);
		$this->template->assign('setting', $setting);


		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();

		$fields = $this->db->list_fields($module['table_name']);
		$this->template->assign(array('module_id'=>$module_id, 'fields'=>$fields));
		$this->template->display('list_add.html');	
	}

	public function edit()
	{
		list($params) = $this->_params();
		$module_id = $params['module_id'];
		$id = $params['id'];
		//提交处理
		$this->_add('list', $params);

		if($id=at_array_val($params, 'id'))
		{
			$config = $this->module_config_model->get($id)->row_array();
			$setting = at_string2array($config['setting']);
		}
		else 
		{
			$config = $setting = array();
		}
		$this->template->assign('data', $config);
		$this->template->assign('setting', $setting);

		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();

		$fields = $this->db->list_fields($module['table_name']);
		$this->template->assign(array('module_id'=>$module_id, 'fields'=>$fields));
		$this->template->display('list_edit.html');	
	}

	/**
	 * 删除操作
	 *
	 * @return void
	 * @author 
	 **/
	public function delete(){
		$id = $this->input->get('id');
		if( $this->module_config_model->delete($id) )
		{
			$this->_ajax_request_return(1, $this->module_config_model->messages());
		}
		else 
		{
			$this->_ajax_request_return(0, $this->module_config_model->errors());	
		}
	}

	public function del_all(){
		$ids = $this->input->post('ids');
		if( empty($ids) )
		{
			$this->_ajax_request_return(0, lang('no_selected'));	
		}
		$sels = explode(',', $ids);
		$succ = 0;
		$fail = 0;
		foreach ($sels as $value) {
			if( $this->module_config_model->delete($value) )
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


	public function view()
	{
		list($params) = $this->_params();
		$module_id = $params['module_id'];

		//提交处理
		$this->_add('view', $params);

		if($id=at_array_val($params, 'id'))
		{
			$config = $this->module_config_model->get($id)->row_array();
			$setting = at_string2array($config['setting']);
			
		}
		else 
		{
			$config = $setting = array();
		}
		$this->template->assign('data', $config);
		$this->template->assign('setting', $setting);

		$this->template->display('list_view_add.html');	
	}

	public function toolbtn()
	{
		list($params) = $this->_params();
		$module_id = $params['module_id'];

		//提交处理
		$this->_add('toolbar', $params);

		if($id=at_array_val($params, 'id'))
		{
			$config = $this->module_config_model->get($id)->row_array();
			$setting = at_string2array($config['setting']);
			
		}
		else 
		{
			$config = $setting = array();
		}
		$this->template->assign('data', $config);
		$this->template->assign('setting', $setting);


		$groups = $this->module_config_model->where(array('module_id'=>$module_id, 'page'=>'list', 'type'=>'toolbar', 'is_group'=>1))->get_data()->result_array();

		$this->template->assign('groups', $groups);
		$this->template->display('list_btn_add.html');	
	}

	public function base()
	{
		list($params) = $this->_params();
		$module_id = $params['module_id'];
		//提交处理
		$this->_add('base', $params);

		$base_config = $this->module_config_model->where(array('module_id'=>$module_id, 'page'=>'list', 'type'=>'base'))->get_data()->row_array();
		if( empty($base_config) )
		{
			$data = array();
		}
		else 
		{
			$data = at_string2array($base_config['setting']);
			$data['id'] = $base_config['id'];
			$data['display_order'] = $base_config['display_order'];
		}
		
		//$module = $this->module_model->get($module_id)->row_array();
		//$this->module_model->free_result();
		$this->template->assign('data', $data);
		$this->template->display('list_base.html');	
	}

	public function save_order()
	{
		list($params) = $this->_params();
		if(IS_POST && IS_AJAX)
		{
			$list_ids = at_array_val($params, 'list_ids', array());
			$this->_save_order($list_ids);

			$view_ids = at_array_val($params, 'view_ids', array());
			$this->_save_order($view_ids);

			$toolbtn_ids = at_array_val($params, 'toolbtn_ids', array());
			$this->_save_order($toolbtn_ids);
		}
		$this->_ajax_request_return(1, lang('gc_module_config_update_successful'));
	}
	private function _save_order($ids)
	{
		list($params) = $this->_params();
		$data = $params['data'];
		foreach ($ids as $id) {
			$update_array = array('display_order'=> $data[$id]['display_order']);
			$this->module_config_model->update($id, $update_array);
		}
	}

	private function _add($type, $params)
	{
		$module_id = $params['module_id'];

		if(IS_POST && IS_AJAX)
		{
			//提交

			$setting = $params['setting'];
			$data = at_array_val($params,'data', array());
			$data = array_merge($data, array('module_id'=>$module_id, 
						  'page'=>'list',
						  'type'=>$type,
						  'setting'=> at_array2string($setting)));
			if( $id=at_array_val($params, 'id') )
			{
				//修改
				$this->module_config_model->update($id, $data);
			}
			else 
			{
				$id = $this->module_config_model->add($data);
			}
			
			if( $id !== FALSE )
			{
				//成功
				$this->_ajax_request_return(1, $this->module_config_model->messages(), $id);
			}
			else 
			{
				//出错
				$this->_ajax_request_return(0, $this->module_config_model->errors());
			}
		}
	}

}

/* End of file list_page.php */
/* Location: .//Users/huangwj/Sites/e-web/apps/gencode/controllers/list_page.php */