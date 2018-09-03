<?php
/**
 * 编辑页面配置接口
 */
namespace app\gencode\controller;

use app\gencode\common\Build;
use app\gencode\model\Edit;
use app\gencode\model\Field;
use app\gencode\model\Page;
use think\Db;
use think\db\Query;
use think\Request;

class EditController
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
            $fieldConfig = Field::where('page_id', $page_id)->select();
            $fields = Edit::where('page_id', $page_id)->select();
            $fieldNames = array_column($fields, 'id', 'name');
            $insertData = [];
            foreach ($tableFields as $item) {
                // 表新增字段
                if (!isset($fieldNames[$item['name']])) {
                    $insertData[] = [
                        'page_id' => $page_id,
                        'name' => $item['name'],
                        'title' => $item['comment'],
                        'default_value' => $item['default'],
                        'update_time' => date('Y-m-d H:i:s'),
                        'status' => 1
                    ];
                }
            }

            $field = new Edit();
            if ($insertData) {
                $field->saveAll($insertData);
            }

            $fields = Edit::where('page_id', $page_id)->select();
            // 已删除的字段
            $updateData = [];
            foreach ($fields as $item) {
                // 表新增字段
                if (!isset($tableFields[$item['name']])) {
                    $updateData[] = ['id' => $item['id'], 'status' => 0];
                }
            }

            if ($updateData) {
                $field->saveAll($updateData);
            }

            $fields = Edit::where('page_id', $page_id)->where('status', 1)->select();
            foreach ($fields as &$item) {
                if (isset($tableFields[$item['name']])) {
                    $tableField = $tableFields[$item['name']];
                    $item['type'] = $tableField['type'];
                    $item['max_length'] = $tableField['max_length'];
                    $item['nullable'] = $tableField['nullable'];
                    $item['default'] = $tableField['default'];
                    $item['is_pri'] = $tableField['is_pri'];
                }
            }

            // 返回
            return responseDataJson($fields);
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
}
