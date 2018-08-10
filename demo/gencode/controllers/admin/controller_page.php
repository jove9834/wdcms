<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Controller_page extends Admin_Controller {

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
		
		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();

		$this->template->assign('module', $module);

		$this->module_config_model->where(array('module_id'=>$module_id,
												'page'=>'controller',
												'type'=>'value'))
								  ->order_by('display_order');

		$defaultValues = $this->module_config_model->get_data()->result_array();
		$this->module_config_model->free_result();

		$this->template->assign(array('module_id'=>$module_id,
									  'values' => $defaultValues));
		$this->template->set_decorators('admin/common/layout.html','');
		$this->template->display('controller_page.html');	
	}

	public function add()
	{
		list($params) = $this->_params();
		$module_id = $params['module_id'];
		//提交处理
		$this->_add('value',$params);

		if($id=at_array_val($params, 'id'))
		{
			$config = $this->module_config_model->get($id)->row_array();
			$setting = at_string2array($config['setting']);
		}
		else 
		{
			$config = $setting = array();
			if($pid = at_array_val($params,'pid',0)){
				$config['pid'] = $pid;
			}
		}
		$this->template->assign('data', $config);
		$this->template->assign('setting', $setting);
		$this->template->assign('module_id', $module_id);

		$module = $this->module_model->get($module_id)->row_array();
		$this->module_model->free_result();



		$fields = $this->db->list_fields($module['table_name']);
		$this->template->assign('fields',$fields);
		$this->template->display('controller_field.html');	
	}

	public function save_order()
	{
		list($params) = $this->_params();
		if(IS_POST && IS_AJAX)
		{
			$ids = $params['ids'];
			$data = $params['data'];
			foreach ($ids as $id) {
				$update_array = array('display_order'=> $data[$id]['display_order']);
				$this->module_config_model->update($id, $update_array);
			}

		}
		$this->_ajax_request_return(1, lang('gc_module_config_update_successful'));
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

	public function base()
	{
		list($params) = $this->_params();
		$module_id = $params['module_id'];
		//提交处理
		$this->_add('base', $params);

		$base_config = $this->module_config_model->where(array('module_id'=>$module_id, 'page'=>'controller', 'type'=>'base'))->get_data()->row_array();
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
		$this->template->display('add_base.html');	
	}

	public function update_order()
	{
		list($params) = $this->_params();
		$module_id = $params['module_id'];

		if(IS_POST && IS_AJAX)
		{
			
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
						  'page'=>'controller',
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

/* End of file controller_page.php */
/* Location: ./apps/gencode/controllers/controller_page.php */