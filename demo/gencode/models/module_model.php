<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module_model extends Base_Model {

	public function __construct()
	{
		parent::__construct();
		$this->table_name = "gc_modules";
	}

	/**
	 * 创建模块
	 *
	 * @param  string $name  组名
	 * @param  string $group_description 组描述
	 * @return int $group_id 返回组ID
	 *
	 * @author Huangwj
	*/
	public function add($data)
	{
		if(!$data['name'])
		{
			$this->set_error('module_name_required');
			return FALSE;
		}
		if(!$data['class_name'])
		{
			$this->set_error('class_name_required');
			return FALSE;
		}

		$existing = $this->db->get_where('gc_modules', array('name' => $data['name']))->num_rows();
		if($existing !== 0)
		{
			$this->set_error('module_name_already_exists');
			return FALSE;
		}
		$existing = $this->db->get_where('gc_modules', array('class_name' => $data['class_name']))->num_rows();
		if($existing !== 0)
		{
			$this->set_error('class_name_already_exists');
			return FALSE;
		}

		$data['ctime'] = time();
		$data['uid'] = LOGIN_UID;
		$data['status'] = 0;
		
		$data = $this->_filter_data($this->table_name, $data);
		// insert the new group
		$this->db->insert($this->table_name, $data);
		$id = $this->db->insert_id();

		// report success
		$this->set_message('module_creation_successful');
		// return the brand new group id
		return $id;
	}

}

/* End of file module_model.php */
/* Location: ./application/models/module_model.php */