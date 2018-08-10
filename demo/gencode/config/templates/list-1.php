<div id="page">
    <div class="page-top">
        <div class="content-menu ib-a blue line-x">
            <a href="{at_url('<?php echo $module['class_name']?>')}" class="active"> <em><?php echo $module['name']?></em>
            </a>
            <?php foreach ($list_page['actions'] as $item): ?>
            <span>|</span>
            <?php
                echo "<a href=\"{at_url('".$item['url']."')}\" title=\"".$item['title']."\"";
                if( isset($item['dialog']) && $item['dialog'] )
                {
                    echo ' data-post="true" data-dismiss="dialog"';
                }
                if( isset($item['new_win']) && $item['new_win'] )
                {
                    echo ' target="_blank"';
                }
                echo '><em>'.$item['title'].'</em></a>';
            ?>
            <?php endforeach; ?>
        </div>
        <?php if (!empty($list_page['search']) ): ?>
        <div class="explain-col">
            <form method="post" action="" name="searchform" id="searchform">
            <?php foreach ($list_page['search'] as $item): ?>
            <?php echo md_input_field($item); ?>
            <?php endforeach; ?>
            &nbsp;&nbsp;
            <input type="submit" value="搜索" class="button" name="search">
            </form>
        </div>
        <?php endif;?>
    </div>
    <div class="page-center">
        <div class="table-list">
            <form action="" method="post" name="myform" id="myform">
                <input name="action" id="action" type="hidden" value="order">
                <table id="table_list" width="100%">
                    <thead>
                        <tr>
                            <?php if(isset($list_page['checkbox']) && $list_page['checkbox']): ?>
                            <th width="20" align="right"><input class="select-all" type="checkbox">&nbsp;</th>
                            <?php endif;?>
                            <?php foreach ($list_page['list'] as $item): ?>
                            <th align="left"><?php echo $item['title']; ?></th>
                            <?php endforeach; ?>
                            <th align="left" class="action">
                            <a class="add" title="添加" href="{at_url('module/module_new')}" data-post="true" data-dismiss="dialog"><i class="icon-plus"></i></a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <%php foreach ($lists as $item): %>
                        <tr>
                            <?php if(isset($list_page['checkbox']) && $list_page['checkbox']): ?>
                            <td align="right"><input name="ids[]" type="checkbox" class="select" value="{$item['id']}">&nbsp;</td>
                            <?php endif;?>
                            <?php foreach ($list_page['list'] as $field): ?>
                            <th align="left">{$item['<?php echo $field['name']; ?>']}</th>
                            <?php endforeach; ?>
                            <td align="left" class="action">
                                <a class="edit" title="修改" href="{at_url('<?php echo $module['class_name']?>/edit',array('id'=>$item['id']))}" data-post="true" data-dismiss="dialog"><i class="icon-pencil"></i></a>
                                &nbsp;&nbsp;
                                <a class="del" title="删除" href="{at_url('<?php echo $module['class_name']?>/delete',array('id'=>$item['id']))}" data-dismiss="delete"><i class="icon-remove"></i></a>
                                &nbsp;&nbsp;
                            </td>
                        </tr>
                        <%php endforeach; %>
                     </tbody>
                     <?php if(isset($list_page['checkbox']) && $list_page['checkbox']): ?>
                     <tfoot>   
                        <tr class="tr-action">
                            <th align="right">
                                <input class="select-all" type="checkbox">&nbsp;</th>
                            <td colspan="7" align="left">
                                <input type="button" class="btn btn-danger btn-sm delete-all" value="删除">
                                
                            </td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </form>
            <?php if(isset($list_page['pagination']) && $list_page['pagination']): ?>
            <div id="pages">{$pages}</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
$(function(){
    $(".table-list tr:odd").addClass("odd");  
    <?php if(isset($list_page['checkbox']) && $list_page['checkbox']): ?>
    var tableList = $.at.tableList({selectAll: true, deleteAll: true, url: "{at_url('module/module_del_all')}"});
    <?php endif; ?>
})
</script>