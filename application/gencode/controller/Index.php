<?php
/**
 *
 */
namespace app\gencode\controller;

use app\gencode\model\Page;
use think\Db;

class Index
{
    public function index()
    {
//        $row = Db::name('page')->find();
//        $row = Page::find();
//        if (!$row) {
//            echo 'empty';
//        } else {
//            $ret = $row->toArray();
//            // echo $row->func_text;
//            var_dump($ret);
//        }

        // return responseDataJson($row);

//        return responseErrorJson("您没有权限", 401, ['login_url' => 'http://xxx.com/login.php'], 401);

        // 查询状态为1的用户数据 并且每页显示10条数据
        $list = Page::paginate(10);
//        var_dump($list);
//         获取分页显示
        $json = $list->render();
        return responseDataJson($json);

        //return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
