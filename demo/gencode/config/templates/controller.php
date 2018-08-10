<%php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

<?php 
	$model = $module_class_name."_model";
?>

class <?php echo ucfirst($module_class_name);?> extends Admin_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->lang->load('<?php echo $module_class_name;?>',SITE_LANGUAGE);
		$this->load->model('<?php echo $model;?>');
	}
	/**
	 * 列表
	 *
	 * @return void
	 * @author 
	 **/
	public function index()
	{	
		list($params, $page, $total) = $this->_params();
		<?php if($keyword): ?>
		if( $keyword = at_array_val($params, 'keyword') )
		{
			$this-><?php echo $model;?>->search_keyword($keyword, array(<?php echo $keyword_field;?>));	
		}
		<?php endif; ?>
		<?php if( $page ): ?>
		//分页
		list($data, $total) = $this-><?php echo $model;?>->limit_page($page, $total, $params);
		$params['total'] = $total;
		$this->template->assign(array(
			'lists'=> $data,
			'pages'=>$this->_pagination(at_url('<?php echo $module_class_name;?>',$params),$total)
		));
		<?php else: ?>
		$this-><?php echo $model;?>->parse_where($params);
		$data = $this-><?php echo $model;?>->get_data()->result_array();
		$this-><?php echo $model;?>->free_result();
		$this->template->assign(array(
			'lists'=> $data
		));
		<?php endif; ?>
		$this->template->set_decorators('admin/common/layout.html','');
		$this->template->display('<?php echo $module_class_name;?>/<?php echo $module_class_name;?>_list.html');	
	}
	/**
	 * 新增操作
	 *
	 * @return void
	 * @author 
	 **/
	public function add()
	{
		list($params) = $this->_params();
		if(IS_POST && IS_AJAX)
		{
			$data = $params['data'];
			//提交
			<?php
			if(!empty($defaultValues))
			{
				foreach($defaultValues as $item)
				{
					$value_setting = at_string2array($item['setting']);
					$value =  md_parse_var($value_setting['value']) ;
			?>
			$data['<?php echo $value_setting["field"]?>'] = <?php echo $value;?>;
			<?php
				}
			}
			?>
			$id = $this-><?php echo $model;?>->add($data);
			if( $id !== FALSE )
			{
				//成功
				<?php 
					$ret_data = '$id';
					if(!$edit_dialog){
						$ret_data = 'at_url(\''.$module_class_name.'\')';
					}
				?>
				$this->_ajax_request_return(1, $this-><?php echo $model;?>->messages(), <?php echo $ret_data;?>);
			}
			else 
			{
				//出错
				$this->_ajax_request_return(0, $this-><?php echo $model;?>->errors());
			}
		}
		<?php if(!$edit_dialog):?>
		$this->template->set_decorators('admin/common/layout.html','');
		<?php endif; ?>
		
		$this->template->display('<?php echo $module_class_name;?>/<?php echo $module_class_name;?>_add.html');	
	}
	/**
	 * 编辑操作
	 *
	 * @return void
	 * @author 
	 **/
	public function edit()
	{
		list($params) = $this->_params();
		$id = $params['id'];
		if(IS_POST && IS_AJAX)
		{
			//提交
			$data = $params['data'];
			$result = $this-><?php echo $model;?>->update($id, $data);
			if( $result !== FALSE )
			{
				//成功
				<?php 
					$ret_data = '$id';
					if(!$edit_dialog){
						$ret_data = 'at_url(\''.$module_class_name.'\')';
					}
				?>
				$this->_ajax_request_return(1, $this-><?php echo $model;?>->messages(), <?php echo $ret_data;?>);
			}
			else 
			{
				//出错
				$this->_ajax_request_return(0, $this-><?php echo $model;?>->errors());
			}
		}

		//根据ID获取对象信息
		$data = $this-><?php echo $model;?>->get($id)->row_array();
		$this-><?php echo $model;?>->free_result();

		$this->template->assign('data', $data);
		<?php if(!$edit_dialog):?>
		$this->template->set_decorators('admin/common/layout.html','');
		<?php endif; ?>
		$this->template->display('<?php echo $module_class_name;?>/<?php echo $module_class_name;?>_edit.html');	
	}
	<?php if($search): ?>
	/**
	 * 查询
	 *
	 * @return void
	 * @author 
	 **/
	public function search()
	{
		if(IS_POST)
		{
			return $this->index();
		}
		
		$this->template->display('<?php echo $module_class_name;?>/<?php echo $module_class_name;?>_search.html');	
	}
	<?php endif;?>
	/**
	 * 查看详细信息
	 *
	 * @return void
	 * @author 
	 **/
	public function view()
	{
		list($params) = $this->_params();
		$id = $params['id'];

		$data = $this-><?php echo $model;?>->get($id)->row_array();
		$this-><?php echo $model;?>->free_result();

		$this->template->assign('data', $data);
		$this->template->set_decorators('common/layout.html','');
		$this->template->display('<?php echo $module_class_name;?>/<?php echo $module_class_name;?>_view.html');	
	}

	/**
	 * 删除操作
	 *
	 * @return void
	 * @author 
	 **/
	public function delete(){
		list($params) = $this->_params();
		$id = $params['id'];
		if( $this-><?php echo $model;?>->delete($id) )
		{
			$this->_ajax_request_return(1, $this-><?php echo $model;?>->messages());
		}
		else 
		{
			$this->_ajax_request_return(0, $this-><?php echo $model;?>->errors());	
		}
	}

	/**
	 * 批量删除操作
	 *
	 * @return void
	 * @author 
	 **/
	public function delete_all(){
		list($params) = $this->_params();
		$ids = $params['ids'];
		if( empty($ids) )
		{
			$this->_ajax_request_return(0, lang('no_selected'));	
		}
		$sels = explode(',', $ids);
		$succ = 0;
		$fail = 0;
		foreach ($sels as $value) {
			if( $this-><?php echo $model;?>->delete($value) )
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
}
/* End of file <?php echo $module_class_name;?>.php */
/* Location: ./application/controller/<?php echo $module_class_name;?>.php */
