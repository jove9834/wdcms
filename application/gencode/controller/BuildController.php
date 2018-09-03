<?php
/**
 * 生成代码
 */
namespace app\gencode\controller;

use app\gencode\model\Page;
use think\Db;
use think\db\Query;
use think\Request;

class BuildController
{
    /**
     * 生成代码
     *
     * @param integer $id   page表中ID
     * @param string  $name
     * @return void
     */
    public function index($id, $name = '')
    {
        $id = intval($id);
        if (!$id) {
            // 参数不正确
        }

        $page = Page::get($id);
        if (!$page) {
            // 配置信息不存在
        }

        // 取eidt配置

    }

    public function model() {

    }

}
