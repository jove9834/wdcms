<?
/**
 * 功能页面接口
 */
namespace app\sys\controller;

use think\Request;
use app\sys\model\Page;

class PageController {
    /**
     * 取资源列表
     *
     * @return \think\Response
     */
    public function index() {
        try {
            $list = Page::paginate(PER_PAGE);
            $json = $list->render();
            return responseDataJson($json);
        }
        catch (\Exception $e) {
            return responseErrorJson($e->getMessage());
        }
    }
    
    /**
     * 保存新建的资源
     *
     * @param \think\Request $request Request
     * @return \think\Response
     */
    public function save(Request $request) {
        $data = $request->param(false);
        try {
            $model = new Page($data);
            $model->save();
            return responseDataJson($model);
        }
        catch (\Exception $e) {
            return responseErrorJson($e->getMessage());
        }
    }
    
    /**
     * 显示指定的资源
     *
     * @param int $id ID
     * @return \think\Response
     */
    public function read($id) {
        $id = intval($id);
        if (!$id) {
            return responseErrorJson('无效参数');
        }
        
        try {
            $row = Page::get($id);
            return $row ? responseDataJson($row) : responseErrorJson('记录不存在', 404);
        }
        catch (\Exception $e) {
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
    public function update(Request $request, $id) {
        $data = $request->param(false);
        try {
            $model = new Page();
            $model->save($data, array(
                'id' => $id
            ));
            return responseDataJson($model);
        }
        catch (\Exception $e) {
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
    public function delete($id) {
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
        }
        catch (\Exception $e) {
            return responseErrorJson('删除失败, 失败原因：' . $e->getMessage());
        }
    }
    
    /**
     * 取用到的字典等资源
     *
     * @return \think\Response
     */
    public function resource() {
        try {
            $resource = array();
            return responseDataJson($resource);
        }
        catch (\Exception $e) {
            return responseErrorJson($e->getMessage());
        }
    }
}
