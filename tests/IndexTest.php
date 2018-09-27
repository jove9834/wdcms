<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: yunwuxin <448901948@qq.com>
// +----------------------------------------------------------------------
namespace tests;

use app\sys\model\LoginAccount;

class IndexTest extends TestCase
{

    public function testBindLoginAccount()
    {
        $ret = LoginAccount::bindLoginAccount(1, '13509334416', LoginAccount::ACCOUNT_TYPE_MOBILE);
        $this->assertTrue($ret ? true : false);

        try {
            $ret = LoginAccount::bindLoginAccount(1, 'wenjinhuang', LoginAccount::ACCOUNT_TYPE_MOBILE);
            $this->assertTrue($ret ? true : false);
        } catch (\Exception $e) {
            $this->assertStringStartsWith('无效的手机号码', $e->getMessage());
        }
    }

    public function testExistsLoginAccount()
    {
        $ret = LoginAccount::existsLoginAccount('13509334416');
        $this->assertTrue($ret);
    }

    public function testGetUserId()
    {
        $uid = LoginAccount::getUserId('13509334416');
        $this->assertTrue(intval($uid) === 1);
    }

    public function testUnBindLoginAccount()
    {
        $ret = LoginAccount::unbindLoginAccount(1, '13509334416');
        $this->assertTrue($ret);
        try {
            $ret = LoginAccount::unbindLoginAccount(1, '13509334411');
            $this->assertFalse($ret);
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() === '账号不存在');
        }
    }

}