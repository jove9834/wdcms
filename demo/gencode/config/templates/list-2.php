<div id="page">
    <div class="page-top bottom-line">
        <nav class="navbar navbar-default">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <a class="navbar-brand" href="#">{lang('page_title')}</a>
            </div>
            <?php if(!empty($views)):?>
            <div class="collapse navbar-collapse">
              <ul class="nav navbar-nav">
                <?php 
                    $idx = 1;
                    foreach ($views as $v) 
                    {
                        echo md_build_view($v, $idx);
                        $idx ++;
                    }    
                ?>
              </ul>
            </div><!-- /.navbar-collapse -->
            <?php endif;?>
        </nav>
        <div class="toolbar clear">
            <?php if(!empty($btns)):?>
            <div class="btn-toolbar pull-left">
                <?php echo md_build_toolbtns($btns);?>
            </div>
            <?php endif;?>
            <?php if( at_array_val($base_setting, 'keyword') ): ?>
            <form id="ls_form" action="{at_url('<?php echo $module_class_name?>')}" method="post">
                <div class="search pull-right span3">
                    <input type="text" placeholder="{lang('keyword_placeholder')}" name="keyword" id="mn_search" class="form-control">
                    <a href="javascript:;">search</a>
                    <input type="hidden" name="type" value="normal_search">
                </div>
            </form>
            <?php endif;?>
        </div>
    </div>
    <div class="page-center">
        <div class="table-list">
            {if empty($lists)}
            <div class="no-data-tip">暂无数据</div>
            {else}
            <form action="" method="post" name="listform" id="listform">
                <table class="table table-hover" id="table_list" width="100%">
                    <thead>
                        <tr>
                            <?php 
                                foreach ($lists as $v) 
                                {
                                    echo md_build_list_header($v);
                                }    
                            ?>
                            <th align="left" width="90">
                                {lang('action')}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <%php foreach ($lists as $item): %>
                        <tr>
                            <?php 
                                foreach ($lists as $v) 
                                {
                                    echo md_build_list_cell($v);
                                }    
                            ?>
                            <td align="left" class="action">
                            <?php if($edit_dialog): ?>
                                <a class="edit btn-icon o-edit" title="{lang('update')}" href="{at_url('<?php echo $module['class_name'];?>/edit', array('id'=>$item['id']))}" data-dismiss="dialog" data-post="true"></a>
                            <?php else: ?>
                                <a class="edit btn-icon o-edit" title="{lang('update')}" href="{at_url('<?php echo $module['class_name'];?>/edit', array('id'=>$item['id']))}"></a>
                            <?php endif;?>
                                <a class="del btn-icon o-trash" title="{lang('delete')}" href="{at_url('<?php echo $module['class_name'];?>/delete', array('id'=>$item['id']))}" data-dismiss="delete"></a>
                            </td>
                        </tr>
                        <%php endforeach; %>
                     </tbody>
                     <tfoot>   
                        <tr class="tr-action">
                            <td align="right">
                                <label class="checkbox">
                                    <input type="checkbox" data-name="ids[]">
                                </label>
                            </td>
                            <td colspan="<?php echo count($lists);?>" align="left">
                                <a class="btn btn-danger btn-sm" href="{at_url('<?php echo $module['class_name'];?>/delete_all')}" data-ckname="ids[]" data-dismiss="delete">{lang('delete')}</a>
                                &nbsp;
                                <?php if( at_array_val($base_setting, 'page') ): ?>
                                <div id="pages" class="pull-right">{$pages}</div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </form>
            {/if}
        </div>
    </div>
</div>
<script>
$(function(){
    $.at.checkbox('ids[]', $('.table-list'));
    <?php if( at_array_val($base_setting, 'keyword') ): ?>
    $('#mn_search').search(function(){
        $('#ls_form').submit();
    }<?php if( at_array_val($base_setting, 'search') ): ?>
    ,function(){
        $.at.dialog({
            title: '高级查询',
            post: true,
            ajax: false,
            url: '{at_url('<?php echo $module_class_name;?>/search')}'
        });
    }
    <?php endif;?>);
    <?php endif; ?>
})
</script>