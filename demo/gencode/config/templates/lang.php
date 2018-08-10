<%php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

<?php foreach($gen_lang as $key=>$value):?>
$lang['<?php echo $key;?>'] = '<?php echo $value;?>';
<?php endforeach;?>


/* End of file <?php echo $module_class_name;?>_lang.php */
/* Location: ./application/language/zh-cn/<?php echo $module_class_name;?>_lang.php */