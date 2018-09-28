<?
/**
 * 功能页面接口
 */
namespace app\sys\controller;

use app\sys\service\UserService;
use think\Controller;
use think\Request;
use app\sys\model\User;

/**
 * Class UcController *
 * @package app\sys\controller */
class UcController extends Controller {

    /**
     * 登录
     *
     * @param \think\Request $request Request
     * @return \think\Response
     */
    public function login(Request $request) {
        $account = trim($request->param('account'));
        $password = trim($request->param('password'));
        try {
            $loginUser = UserService::login($account, $password);
            return responseDataJson($loginUser);
        } catch (\Exception $e) {
            return responseErrorJson($e->getMessage(), $e->getCode());
        }
    }

    /**
     * 登录
     *
     * @param \think\Request $request Request
     * @return \think\Response
     */
    public function logout(Request $request) {
        // 验证登录
        $token = $request->header('ACCESS_TOKEN');
        if (!$token) {
            return responseErrorJson('未登录');
        }


    }
}
