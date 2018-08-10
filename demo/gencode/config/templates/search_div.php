<div class="form-view">
	<form id="my_form" class="form-horizontal">
	<div class="form-container">
		<?php 
			$script_files = "";
			$scripts = "";
			foreach ($fields as $v)
            {
            	$setting = at_string2array($v['setting']);
            	list($html, $script, $script_file) = md_build_field($setting, 'search', $module_class_name); 
            		
        ?>
    	<div class="control-group">
		    <label class="control-label">{lang('<?php echo $setting['field'];?>')}</label>
		    <div class="controls">
		    <?php echo $html; ?>
		    <?php if(at_array_val($setting, 'tips')):?>
				<p class="help-block"><i class="icon-info-sign"></i>{lang('tip_<?php echo $setting['field'];?>')}</p>
            <?php endif; ?>
        	</div>
            <?php
        		if( !empty($script)  ) $scripts .= $script;
        		if( !empty($script_file)  ) $script_files .= $script_file;
        			
        	?>
		</div>
        <?
            }    
        ?>
	</div>
	</form>
</div>
<?php echo $script_files; ?>
<script type="text/javascript">
$(function(){
	<?php echo $scripts; ?>
});
function at_form_check(){
	return true;
}
</script>