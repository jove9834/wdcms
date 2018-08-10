<%php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
<?php 
	$model = $module_class_name."_model";
?>
class <?php echo ucfirst($model);?> extends Base_Model {
	public function __construct()
	{
		parent::__construct();
		$this->table_name = "<?php echo $table_name;?>";
	}

}

/* End of file <?php echo $model;?>.php */
/* Location: ./apps/gencode/models/<?php echo $model;?>.php */