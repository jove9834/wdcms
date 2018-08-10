<div class="form-view">
	<form id="my_form">
	<div class="form-group">
		<div class="group-top"></div>
				<div class="form-row">
			<div class="form-item-2">
				<input type="text" help="" /></div>			</div>
		</div>
				<div class="form-row">
			<div class="form-item-2">
				<div class="title">性别</div><div class="input"><input type="radio" id="sex_1" title="性别" ><label for="sex_1">男</label><input type="radio" id="sex_2" title="性别" ><label for="sex_2">女:1</label></div>			</div>
		</div>
				<div class="form-row">
			<div class="form-item-2">
				<input type="text" help="" /></div>			</div>
		</div>
				<div class="group-footer"></div>
	</div>
	</form>
</div>
<script type="text/javascript" src="{base_url('assets/js/validate.js')}"></script>
<script type="text/javascript">
function at_form_check(){
	return $('#my_form').validateForm().check();
}
</script>