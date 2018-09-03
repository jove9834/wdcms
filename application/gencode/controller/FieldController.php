<?php
/**
 * 字段配置接口
 */
namespace app\gencode\controller;

use app\gencode\common\Build;
use app\gencode\model\Field;
use app\gencode\model\Page;
use think\Db;
use think\db\Query;
use think\Request;

class FieldController
{
    /**
     * 取Page列表
     *
     * @param integer $page_id 页面ID
     * @return \think\Response
     */
    public function index($page_id)
    {
        if (!$page_id) {
            return responseErrorJson('参数不正确');
        }

        try {
            $page = Page::get($page_id);
            if (!$page) {
                return responseErrorJson('page不存在');
            }

            // 取表字段定义
            $tableFields = Build::getTableFields($page['connection'], $page['table']);
            // 取字段
            $fields = Field::where('page_id', '=', $page_id)->select();
            if ($fields->isEmpty()) {
                // 初始化field表
                $insertData = [];
                foreach ($tableFields as $field) {
                    $insertData[] = [
                        'page_id' => $page_id,
                        'name' => $field['name'],
                        'title' => $field['comment'],
                        'default_value' => $field['default'],
                        'update_time' => date('Y-m-d H:i:s'),
                    ];
                }
                $field = new Field();
                $field->saveAll($insertData);

                $fields = Field::where('page_id', '=', $page_id)->select();
            } else {
                // TODO::验证表结构是否修改

            }

            // 返回
            return responseDataJson($fields);
        } catch (\Exception $e) {
            return responseErrorJson($e->getMessage());
        }
    }

    /**
     * 保存新建的资源
     *
     * @param \think\Request $request Request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        $data = $request->param(false);
        try {
            $page = new Page($data);
            $page->save();
            return responseDataJson($page);
        } catch (\Exception $e) {
            return responseErrorJson($e->getMessage());
        }
    }

    /**
     * 显示指定的资源
     *
     * @param int $id ID
     * @return \think\Response
     */
    public function read($id)
    {
        $id = intval($id);
        if (!$id) {
            return responseErrorJson('无效参数');
        }

        try {
            $row = Page::get($id);
            return $row ? responseDataJson($row) : responseErrorJson('记录不存在', 404);
        } catch (\Exception $e) {
            return responseErrorJson($e->getMessage());
        }
    }

    /**
     * 保存更新的资源
     *
     * @param \think\Request $request Request
     * @param int            $id      ID
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        // $data = Request::only(['name','email']);
        $data = $request->param(false);
        try {
            $page = new Page();
            $page->save($data, ['id' => $id]);
            return responseDataJson($page);
        } catch (\Exception $e) {
            return responseErrorJson($e->getMessage());
        }
    }

    /**
     * 删除指定资源
     *
     * @param int $id ID
     * @return \think\Response
     * @throws \Exception
     */
    public function delete($id)
    {
        $id = intval($id);
        if (!$id) {
            return responseErrorJson('无效参数');
        }

        $row = Page::get($id);
        if (!$row) {
            return responseErrorJson('记录不存在', 404);
        }

        try {
            return $row->delete() ? responseDataJson('删除成功') : responseErrorJson('删除失败');
        } catch (\Exception $e) {
            return responseErrorJson('删除失败, 失败原因：' . $e->getMessage());
        }
    }

    /**
     * 取数据库的所有表
     *
     * @param string $conn 数据库连接名
     * @return \think\Response
     */
    public function tables($conn = NULL)
    {
        /** @var \think\db\Query $query*/
        try {
            $query = Db::connect($conn);
            $tables = $query->getConnection()->getTables($query->getConfig('database'));
            return responseDataJson($tables);
        } catch (\Exception $e) {
            return responseErrorJson($e->getMessage());
        }
    }
}
