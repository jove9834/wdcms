<div class="form-view">
	<form id="my_form">
	<div class="form-group">
		<div class="group-top"></div>
		<table width="100%" class="table-form">
			<?php 
				$script_files = "";
				$scripts = "";
				foreach ($fields as $v)
                {
                	$setting = at_string2array($v['setting']);
                	if( $setting['edit_status'] == 'hide' )
                	{
                		continue;
                	}
            ?>
            <tr>
	            <th width="100">{lang('<?php echo $setting['field'];?>')}： </th>
	            <td>
	            	<?php 
	            		list($html, $script, $script_file) = md_build_field($setting, 'view', $module_class_name); 
	            		echo $html;
	            		if( !empty($script)  ) $scripts .= $script;
	            		if( !empty($script_file)  ) $script_files .= $script_file;
	            			
	            	?>
	            </td>
	        </tr>
	        <?
                }    
            ?>
		</table>
		<div class="group-footer"></div>
	</div>
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