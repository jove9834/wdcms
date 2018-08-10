<div class="form-view">
	<form id="my_form">
	<div class="form-group">
		<?php
			$script_files = "";
			$scripts = "";
			$this->module_config_model->where(
					array('module_id'=>$module_id,
						  'page'=>'add',
						  'type'=>'tab'))
				 ->order_by('display_order');

			$tabs = $this->module_config_model->get_data()->result_array();
			$this->module_config_model->free_result();
			$has_tabs = !empty($tabs);
		?>
		<?php if($has_tabs): ?>
		<ul class="nav nav-tabs">
			<?php 
			foreach ($tabs as $i => $v) {
				$setting = at_string2array($v['setting']);
			?>
			<li class="<?php if($i==0) echo 'active';?>"><a href="#tab<?php echo $i;?>" data-toggle="tab"><?php echo $setting['name'];?></a>
			</li>
			<?php } ?>
		</ul>
		<div class="tab-content">
		<?php endif; ?>
		<?php
			if(!$has_tabs)
			{
				$tabs = array(array('id'=>0, 'setting'=>''));
			}
			else
			{
				$tabs = array_merge($tabs, array(array('id'=>0, 'setting'=>'')));
			}
		?>
		<?php
			foreach ($tabs as $i => $tab) {
				$this->module_config_model->where(
						array('module_id'=>$module_id,
							  'page'=>'add',
							  'type'=>'group',
							  'pid'=>$tab['id']
						))
					 ->order_by('display_order');

				$groups = $this->module_config_model->get_data()->result_array();
				$this->module_config_model->free_result();
				if(empty($groups))
				{
					$groups = array( array('id'=>$tab['id'], 'setting'=>'') );
				}
				else
				{
					$groups = array_merge($groups, array(array('id'=>$tab['id'], 'setting'=>'')));
				}
		?>
		<?php if($tab['id'] > 0): ?>
		<div id="tab<?php echo $i;?>" class="tab-pane <?php if($i==0) echo 'active';?>">
		<?php endif; ?>
		<?php
			foreach ($groups as $gi => $group) {
				$has_group = true;
				if(empty($group['setting']))
				{
					$has_group = false;
				}
				else 
				{
					$setting = at_string2array($group['setting']);
					$name = $setting['name'];
				}
				$this->module_config_model->where(
						array('module_id'=>$module_id,
							  'page'=>'add',
							  'type'=>'field',
							  'pid'=>$group['id']
						))
					 ->order_by('display_order');

				$fields = $this->module_config_model->get_data()->result_array();
				$this->module_config_model->free_result();
		?>
		<?php if($has_group): ?>
		<div class="group-top">
			<div class="group-title"><?php echo $name;?></div>		
		</div>
		<?php endif; ?>

		<table width="100%" class="table-form">
			<?php 
				foreach ($fields as $v)
                {
                	$setting = at_string2array($v['setting']);
                	if( $setting['edit_status'] == 'hide' )
                	{
                		continue;
                	}
                	list($html, $script, $script_file) = md_build_field($setting, 'edit', $module_class_name); 
                	if( $display == 'hidden')
                	{
                		$hiddens .= $html;
                		continue;
                	}
            ?>
            <tr>
	            <th width="100">{lang('<?php echo $setting['field'];?>')}： </th>
	            <td>
	            	<?php 
	            		echo $html;
	            	?>
	            	<?php if(at_array_val($setting, 'tips')):?>
					<div class="valid-tip"><i class="icon-info-sign"></i>{lang('tip_<?php echo $setting['field'];?>')}</div>
	            	<?php endif; ?>
	            	<?php
	            		if( !empty($script)  ) $scripts .= $script;
	            		if( !empty($script_file)  ) $script_files .= $script_file;
	            			
	            	?>
	            </td>
	        </tr>
	        <?
                }    
            ?>
		</table>
		<?php } ?>
		<?php if($tab['id'] > 0): ?>
		</div>
		<?php endif; ?>
		<?php } ?>
		<?php if($has_tabs): ?>
		</div>
		<?php endif; ?>
	</div>
	<?php echo $hiddens;?>
	</form>
</div>
<script type="text/javascript" src="{base_url('assets/js/validate.js')}"></script>
<?php echo $script_files; ?>
<script type="text/javascript">
$(function(){
	<?php echo $scripts; ?>
});
function at_form_check(){
	return $('#my_form').validateForm().check();
}
</script>