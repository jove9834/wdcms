<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Module_config_model extends Base_Model {
	public function __construct()
	{
		parent::__construct();
		$this->table_name = "gc_module_config";
		//$this->unique = 
	}

}

/* End of file module_config_model.php */
/* Location: .//Users/huangwj/Sites/0034/apps/gencode/models/module_config_model.php */