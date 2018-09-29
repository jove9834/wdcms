<?php
/**
 * 功能页面接口
 */
namespace app\sys\controller;

use app\sys\service\UserService;
use think\Controller;
use think\Request;
use app\sys\model\UserValidate;

/**
 * Class UcController
 * @package app\sys\controller
 */
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
        if (! UserService::isLogined()) {
            return responseDataJson('success');
        }

        UserService::logout();
        return responseDataJson('success');
    }

    /**
     * 取当前登录用户信息
     *
     * @return \think\response\Json
     */
    public function loginUser(){
        try {
            // 验证登录
            if (! UserService::isLogined()) {
                return responseErrorJson('未登录或登录超时', 403, [], 403);
            }

            $user = UserService::getLoginUser();
            $user = $user->hidden(['password', 'salt'])->toArray();
            return responseDataJson($user);
        } catch (\Exception $e) {
            return responseErrorJson($e->getMessage(), $e->getCode());
        }
    }
}
