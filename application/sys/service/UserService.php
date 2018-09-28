<?php
/**
 * <<文件说明>>
 *
 * @author        黄文金
 * @copyright  Copyright (c) 2014 - 2015, Wedo, Inc. (http://weidu178.com/)
 * @link            http://weidu178.com
 * @since          Version 1.0
 */
namespace app\sys\service;

use app\sys\model\LoginAccount;
use app\sys\model\User;
use app\sys\model\UserProfile;
use think\facade\Cache;
use think\facade\Config;
use think\facade\Request;
use think\facade\Validate;

/**
 * Class UserService
 *
 * @package app\sys\service
 */
class UserService
{
    /**
     * 加密代码长度
     *
     * @var integer
     */
    const SALT_LENGTH = 6;

    /**
     * 锁定时间，单位秒
     *
     * @var integer
     */
    const LOCKED_TIME = 300;

    /**
     * 缓存有效期, 30天
     *
     * @var integer
     */
    const CACHE_EXPIRE = 2592000; // 为空表示无限期

    /**
     * 最大尝试登录次数
     *
     * @var integer
     */
    const MAXIMUM_LOGIN_ATTEMPTS = 5;

    /**
     * COOKIE验证串
     *
     * @var string
     */
    const COOKIE_SECURITY_CODE = '3fcf273f383d324ccb05aaec9fcc0ec7a0a2a67e';

    /**
     * cookie code
     *
     * @var string
     */
    static $cookieCode = NULL;

    /**
     * 登录用户ID
     *
     * @var integer
     */
    static $loginUid = NULL;

    /**
     * 登录处理
     *
     * @param string $account  帐号
     * @param string $password 密码
     * @throws \Exception 1.帐号和密码不能为空 2.帐号或密码错误 3.帐号已被删除 4.帐号未激活, 5.帐号被锁定
     * @return array 返回登录信息
     */
    public static function login($account, $password) {
        if (! $account || ! $password) {
            throw new \Exception('帐号、密码不能为空', 1);
        }

        if(static::isTimeLocked($account)) {
            // 帐号登录出错次数超过最大次数，被锁定
            throw new \Exception(sprintf('登录出错次数超过%d次，帐号已被锁定', self::MAXIMUM_LOGIN_ATTEMPTS), 5);
        }

        $user = self::getUserByAccount($account);
        if (! $user) {
            // 帐号不存在
            $msg = lang('帐号不存在');
            LogService::addLog('login', sprintf('error:%s, account:%s', $msg, $account));
            throw new \Exception('帐号或密码不正确', 2);
        }

        // 判断用户密码
        $hashPassword = static::hashPassword($password, $user->salt);
        if (strcasecmp($hashPassword, $user->password) !== 0) {
            // 密码不正确
            self::increaseLoginAttempts($account);
            $msg = '密码不正确';
            LogService::addLog('login', sprintf('error:%s, account:%s', $msg, $account));
            throw new \Exception('帐号或密码不正确', 2);
        }

        // 判断用户状态
        if ($user->status === User::USER_STATUS_DISABLED) {
            // 帐号已禁用
            throw new \Exception('帐号已禁用', 3);
        }

        $loginUser = ['user_id' => $user->id];

        // 生成CookieKey
        $token = self::getCookieCode();
        self::saveSession($token, $loginUser);

        // 写日志
        LogService::addLog('login', $account, $user->id);
        // 清除登录尝试记录
        self::clearLoginAttempts($account);
        return [
            'user_id' => $user->id,
            'real_name' => $user->real_name,
            'avatar' => $user->avatar,
            'access_token' => $token
        ];
    }

    /**
     * 退出登录
     *
     * @return void
     */
    public static function logout() {
        $userId = self::getLoginUserId();
        self::destorySession();
        if ($userId) {
            // 写日志
            LogService::addLog('logout', NULL, $userId);
        }
    }

    /**
     * 判断是否登录状态
     *
     * @return boolean
     **/
    public static function isLogined() {
        // 取access token

        $uid = self::getLoginUserId();

        return $uid ? TRUE : FALSE;
    }

    /**
     * 取登录用户信息
     *
     * @return array
     */
    public static function getLoginUser() {
        // 取得cacheKey
        $data = Cache::get(self::getCookieCode());
        return $data ? json_decode($data, TRUE) : NULL;
    }

    /**
     * 取当前登录用户ID
     *
     * @return integer|null
     */
    public static function getLoginUserId() {
        if (! self::$loginUid) {
            $loginUser = static::getLoginUser();
            self::$loginUid = $loginUser ? $loginUser['user_id'] : NULL;
        }

        return self::$loginUid;
    }

    /**
     * 创建帐号
     *
     * @param string  $account  帐号
     * @param string  $password 密码
     * @param integer $type     帐号类型
     * @param User    $info     用户信息
     * @return User 1.帐号不能为空 2.帐号已存在 3.帐号已被删除 4.帐号未激活, 5.帐号被锁定
     * @throws \Exception 1.帐号不能为空 2.帐号已存在 3.帐号已被删除 4.帐号未激活, 5.帐号被锁定
     */
    public static function createAccount($account, $password = NULL, $type = LoginAccount::ACCOUNT_TYPE_EMAIL, User $info = NULL) {
        if (! $account) {
            throw new \Exception(lang('帐号不能为空'), 1);
        }

        // 判断帐号是否存在
        if (self::existsLoginAccount($account)) {
            throw new \Exception('帐号已存在', 2);
        }

        // TODO:: 密码规则验证

        try {
            $info == NULL && $info = new User();
            $info->salt = self::generateSalt();
            $info->password = self::hashPassword($password, $info->salt);

            // 对密码进行加密
            $userId = $info->save();

            if (! $userId) {
                throw new \Exception('创建帐号出现异常');
            }

            // 添加帐号
            self::bindLoginAccount($info, $account, $type);

            // 写创建账号成功日志
            LogService::addLog('user_add', '创建账号成功', $userId);
            return $info;
        } catch (\Exception $e) {
            LogService::addLog('user_add', sprintf('创建帐号出现异常， 异常信息：%s', $e->getMessage()));
            throw $e;
        }
    }

    /**
     * 修改密码
     *
     * @param int|User $user     用户ID或用户模型
     * @param string   $password 新的密码
     * @throws \Exception 异常
     * @return bool|int
     */
    public static function updatePassword($user, $password) {
        $user = self::checkUserParameter($user);

        $password = trim($password);
        if (!$password) {
            throw new \Exception('密码不能为空');
        }

        // TODO: 密码验证规则
        $user->salt = self::generateSalt();
        $user->password = self::hashPassword($password, $user->salt);
        return $user->allowField(['password', 'salt'])->save();
    }

    /**
     * 保存登录状态至Session， 注意，这里的session可以是缓存如redis
     *
     * @param string    $cookieCode cookie代码
     * @param array     $user       用户数据
     * @return void
     */
    private static function saveSession($cookieCode, array $user) {
        self::$loginUid = $user['user_id'];
        Cache::set($cookieCode, json_encode($user), self::CACHE_EXPIRE);
    }

    /**
     * 注销Session
     *
     * @return void
     */
    private static function destorySession() {
        $code = self::getCookieCode();
        Cache::rm($code);
        self::$cookieCode = NULL;
        self::$loginUid = NULL;
    }

    /**
     * 获取COOKIE保存的CODE
     *
     * @param boolean $force 当CODE为空时是否强制刷新
     * @return string 32位的MD5 KEY
     */
    private static function getCookieCode($force = FALSE) {
        if ($force) {
            // 强制生成cookieCode
            self::$cookieCode = self::generateUserCookie();
        }

        if (! self::$cookieCode) {
            self::$cookieCode = is_array($_COOKIE) && isset($_COOKIE['__USER__']) ? $_COOKIE['__USER__'] : '';
            // 如果CODE为空，则再去生成下
            if (! self::$cookieCode) {
                self::$cookieCode = self::generateUserCookie();
            }
        }

        return self::$cookieCode;
    }

    /**
     * 设置USER登陆信息
     *
     * @return string 返回CODE
     */
    private static function generateUserCookie() {
        $code = md5(self::COOKIE_SECURITY_CODE . '-' . microtime() . '-' . mt_rand());
        $cookie_domain = Config::get('app.cookie_domain');
        if ($cookie_domain) {
            setcookie('__USER__', $code, time() + self::CACHE_EXPIRE, '/', $cookie_domain);
        } else {
            setcookie('__USER__', $code, time() + self::CACHE_EXPIRE, '/');
        }

        return $code;
    }

    /**
     * 密码加密
     *
     * @param string $password 明文密码
     * @param string $salt     加密代码
     * @return string 返回加密后的字符串
     */
    public static function hashPassword($password, $salt = '') {
        if (empty($password)) {
            return FALSE;
        }

        return sha1($password . $salt);
    }

    /**
     * 生成随机的加密代码.
     *
     * @param int $len 生成的随机代码长度，默认为6位
     * @return string
     */
    public static function generateSalt($len = 6) {
        return substr(md5(uniqid(rand(), true)), 0, $len);
    }

    /**
     * 帐号是否超过尝试登录次数被锁定
     *
     * @param string $account 帐号
     * @return boolean
     */
    private static function isTimeLocked($account) {
        // 取timelocked key
        $key = self::getTimeLockedCacheKey($account);
        $timeLocked = Cache::get($key);
        return intval($timeLocked) === 1;
    }

    /**
     * 增加尝试登录记录
     *
     * @param string $account 帐号
     * @return void
     */
    private static function increaseLoginAttempts($account) {
        if (self::MAXIMUM_LOGIN_ATTEMPTS <= 0) {
            return;
        }

        $key = self::getLoginAttemptCacheKey($account);
        if (!Cache::has($key)) {
            Cache::set($key, 0, 86400);
        }

        $attempts = Cache::inc($key);
        if ($attempts >= self::MAXIMUM_LOGIN_ATTEMPTS) {
            // 超过次数，锁定
            Cache::set(self::getTimeLockedCacheKey($account), 1, self::LOCKED_TIME);
            // 清除尝试记录
            self::clearLoginAttempts($account);
        }
    }

    /**
     * 清除登录尝试记录
     *
     * @param string $account 帐号
     * @return void
     */
    private static function clearLoginAttempts($account) {
        $key = self::getLoginAttemptCacheKey($account);
        Cache::rm($key);
    }

    /**
     * 获取尝试登录记录缓存Key
     *
     * @param string $account 帐号
     * @return string
     */
    private static function getLoginAttemptCacheKey($account) {
        $ip_address = Request::ip();
        return md5($account . '_' . $ip_address);
    }

    /**
     * 获取尝试登录记录缓存Key
     *
     * @param string $account 帐号
     * @return string
     */
    private static function getTimeLockedCacheKey($account) {
        $ip_address = Request::ip();
        return md5($account . '_' . $ip_address . '_timelocked');
    }

    /**
     * 检查User参数
     *
     * @param int|User $user user参数
     * @return User
     * @throws \think\Exception\DbException DB异常
     * @throws \Exception 异常
     */
    private static function checkUserParameter($user) {
        if (!$user) {
            throw new \Exception('用户不存在');
        }

        if (is_int($user)) {
            $user = User::get($user);
            if (!$user) {
                throw new \Exception('用户不存在');
            }
        }

        return $user;
    }

    /**
     * 取用户属性
     *
     * @param integer|User $user 用户ID或用户模型
     * @param string       $name  属性名称，为空时，取所有属性，返回数组
     * @return array|UserProfile 当$name为空时，返回一维array，否则返回属性值
     * @throws \think\Exception\DbException DB异常
     * @throws \Exception 异常
     */
    public static function getUserProfile($user, $name = NULL) {
        $user = self::checkUserParameter($user);
        $data = ['user_id' => $user->id];
        if ($name) {
            $data['name'] = trim($name);
            return UserProfile::get($data);
        } else {
            return UserProfile::all($data);
        }
    }

    /**
     * 设置用户属性
     *
     * @param integer|User $user 用户ID或用户模型
     * @param string       $name  属性名称
     * @param string       $value 属性值
     * @return boolean
     * @throws \think\Exception\DbException DB异常
     * @throws \Exception 异常
     */
    public static function setUserProfile($user, $name, $value) {
        $user = self::checkUserParameter($user);

        $name = trim($name);
        $value = trim($value);
        if (!$name) {
            throw new \Exception('属性名称为空');
        }

        if (is_null($value) || $value === '') {
            throw new \Exception('属性值为空');
        }

        // 判断账号是否已存在
        $profile = self::getUserProfile($user, $name);
        if ($profile) {
            // 更新
            $profile->value = $value;
        } else {
            $profile = new UserProfile([
                'user_id' => $user->id,
                'name' => $name,
                'value' => $value
            ]);
        }

        return $profile->save();
    }

    /**
     * 删除用户属性
     *
     * @param integer|User $user 用户ID或用户模型
     * @param string       $name 属性名称
     * @return int
     * @throws \think\Exception\DbException DB异常
     * @throws \Exception 异常
     */
    public static function deleteUserProfile($user, $name = NULL) {
        $user = self::checkUserParameter($user);

        $data = ['user_id' => $user->id];
        if ($name) {
            $data['name'] = trim($name);
        }

        return UserProfile::destroy($data);
    }

    /**
     * 绑定登录账号
     *
     * @param int|User $user    用户ID或用户模型
     * @param string   $account 账号名称
     * @param int      $type    账号类型
     * @return bool|int
     * @throws \think\Exception\DbException DB异常
     * @throws \Exception 异常
     */
    public static function bindLoginAccount($user, $account, $type = LoginAccount::ACCOUNT_TYPE_USERNAME) {
        $account = trim($account);
        $user = self::checkUserParameter($user);

        if (!$account) {
            throw new \Exception('账号不能为空');
        }

        if ($type != LoginAccount::ACCOUNT_TYPE_USERNAME && $type != LoginAccount::ACCOUNT_TYPE_MOBILE && $type != LoginAccount::ACCOUNT_TYPE_EMAIL) {
            throw new \Exception('账号类型不正确');
        }

        if ($type === LoginAccount::ACCOUNT_TYPE_EMAIL) {
            // 验证邮箱格式
            if (!Validate::is($account,'email')) {
                throw new \Exception('无效的邮箱地址');
            }
        } else if ($type === LoginAccount::ACCOUNT_TYPE_MOBILE) {
            // 验证手机号码
            if (!Validate::is($account,'mobile')) {
                throw new \Exception('无效的手机号码');
            }
        } else {
            // 验证用户名格式
            if (!Validate::is($account,'alphaDash')) {
                throw new \Exception('无效的用户名，用户名只能是字母、数字、-、_组成');
            }

            if (!Validate::length($account,'4,15')) {
                throw new \Exception('无效的用户名，用户名最少4个字符，最多不超过15个字符');
            }
        }

        // 判断账号是否已存在
        if (self::existsLoginAccount($account)) {
            throw new \Exception('账号已存在');
        }

        $loginAccount = new LoginAccount([
            'user_id' => $user->id,
            'account' => $account,
            'account_type' => $type
        ]);

        return $loginAccount->save();
    }

    /**
     * 登录账号解绑
     *
     * @param int|User $user    用户ID或用户模型
     * @param string   $account 账号名称
     * @return boolean
     * @throws \think\Exception\DbException DB异常
     * @throws \Exception 异常
     */
    public static function unbindLoginAccount($user, $account) {
        $account = trim($account);
        $user = self::checkUserParameter($user);

        if (!$account) {
            throw new \Exception('账号不能为空');
        }

        $loginAccount = LoginAccount::get([
            'user_id' => $user->id,
            'account' => $account
        ]);

        if (!$loginAccount) {
            throw new \Exception('账号不存在');
        }

        return $loginAccount->delete() > 0;
    }

    /**
     * 判断登录账号是否存在
     *
     * @param string $account 账号名
     * @return bool
     * @throws \think\exception\DbException
     * @throws \Exception 异常
     */
    public static function existsLoginAccount($account) {
        $account = trim($account);
        if (!$account) {
            throw new \Exception('参数不正确：账号不能为空');
        }

        $loginAccount = LoginAccount::get(['account' => $account]);
        return $loginAccount ? TRUE : FALSE;
    }

    /**
     * 根据登录账号取对应的用户ID
     *
     * @param string $account 账号名
     * @return int|null
     * @throws \think\exception\DbException
     * @throws \Exception 异常
     */
    public static function getUserIdByAccount($account) {
        $account = trim($account);
        if (!$account) {
            throw new \Exception('参数不正确：账号不能为空');
        }

        $loginAccount = LoginAccount::get(['account' => $account]);
        return $loginAccount ? $loginAccount->user_id : NULL;
    }

    /**
     * 根据登录账号取用户信息
     *
     * @param string $account 账号名
     * @return User|null
     * @throws \think\exception\DbException 异常
     */
    public static function getUserByAccount($account) {
        $userId = self::getUserIdByAccount($account);
        return User::get($userId);
    }
}