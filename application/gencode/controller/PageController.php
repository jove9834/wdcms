<?php
/**
 * 功能页面接口
 */
namespace app\gencode\controller;

use app\gencode\model\Page;
use think\Db;
use think\db\Query;
use think\facade\App;
use think\Request;

class PageController
{
    /**
     * 取Page列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $a = App::config();
        var_dump($a);
//        echo App::getModulePath();
        // 查询状态为1的用户数据 并且每页显示10条数据
        try {
            $list = Page::paginate(10);
            $json = $list->render();
            return responseDataJson($json);
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

    /**
     * 取表字段信息
     *
     * @param integer $id Page ID
     * @return \think\response\Json
     */
    public function fields($id) {
        $id = intval($id);
        if (!$id) {
            return responseErrorJson('无效参数');
        }

        try {
            $row = Page::get($id);
            if (!$row) {
                return responseErrorJson('记录不存在');
            }

            $sql = 'select * from information_schema.columns where table_schema=:dbname and table_name=:tableName';
            /** @var Query $query */
            $query = Db::connect($row['connection']);
            $bind = array(
                'dbname' => $query->getConfig('database'),
                'tableName' => $query->getConfig('prefix') . $row['table']
            );

            $rows = $query->query($sql, $bind);
            $fields = [];
            if ($rows) {
                foreach ($rows as $item) {
                    $fields[] = [
                        'name' => $item['COLUMN_NAME'],
                        'type' => $item['DATA_TYPE'],
                        'max_length' => $item['CHARACTER_MAXIMUM_LENGTH'],
                        'comment' => $item['COLUMN_COMMENT'],
                        'nullable' => $item['IS_NULLABLE'] === 'YES',
                        'default' => $item['COLUMN_DEFAULT'],
                        'is_pri' => $item['COLUMN_KEY'] === 'PRI',
                    ];
                }
            }

            return responseDataJson($fields);
        } catch (\Exception $e) {
            return responseErrorJson($e->getMessage());
        }
    }
}
