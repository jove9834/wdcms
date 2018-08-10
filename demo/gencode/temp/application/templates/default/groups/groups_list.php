<div id="page">
    <div class="page-top">
        <div class="content-menu ib-a blue line-x">
            <a href="{at_url('user')}" class="active"> <em>用户管理</em>
            </a>
                        <span>|</span>
            <a href="{at_url('')}" title="新增用户" data-post="true" data-dismiss="dialog"><em>新增用户</em></a>                        <span>|</span>
            <a href="{at_url('http://www.baidu.com')}" title="帮助"><em>帮助</em></a>                    </div>
                <div class="explain-col">
            <form method="post" action="" name="searchform" id="searchform">
                        <input type="text" placeholder="" /></div>                        &nbsp;&nbsp;
            <input type="submit" value="搜索" class="button" name="search">
            </form>
        </div>
            </div>
    <div class="page-center">
        <div class="table-list">
            <form action="" method="post" name="myform" id="myform">
                <input name="action" id="action" type="hidden" value="order">
                <table id="table_list" width="100%">
                    <thead>
                        <tr>
                                                        <th width="20" align="right"><input class="select-all" type="checkbox">&nbsp;</th>
                                                                                    <th align="left">会员名称</th>
                                                        <th align="left">注册时间</th>
                                                        <th align="left" class="action">
                            <a class="add" title="添加" href="{at_url('module/module_new')}" data-post="true" data-dismiss="dialog"><i class="icon-plus"></i></a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lists as $item): %>
                        <tr>
                                                        <td align="right"><input name="ids[]" type="checkbox" class="select" value="{$item['id']}">&nbsp;</td>
                                                                                    <th align="left">{$item['user_name']}</th>
                                                        <th align="left">{$item['ctime']}</th>
                                                        <td align="left" class="action">
                                <a class="edit" title="修改" href="{at_url('user/edit',array('id'=>$item['id']))}" data-post="true" data-dismiss="dialog"><i class="icon-pencil"></i></a>
                                &nbsp;&nbsp;
                                <a class="del" title="删除" href="{at_url('user/delete',array('id'=>$item['id']))}" data-dismiss="delete"><i class="icon-remove"></i></a>
                                &nbsp;&nbsp;
                            </td>
                        </tr>
                        <?php endforeach; %>
                     </tbody>
                                          <tfoot>   
                        <tr class="tr-action">
                            <th align="right">
                                <input class="select-all" type="checkbox">&nbsp;</th>
                            <td colspan="7" align="left">
                                <input type="button" class="btn btn-danger btn-sm delete-all" value="删除">
                                
                            </td>
                        </tr>
                    </tfoot>
                                    </table>
            </form>
                    </div>
    </div>
</div>
<script>
$(function(){
    $(".table-list tr:odd").addClass("odd");  
        var tableList = $.at.tableList({selectAll: true, deleteAll: true, url: "{at_url('module/module_del_all')}"});
    })
</script>