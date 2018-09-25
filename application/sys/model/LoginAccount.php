<?
namespace app\sys\model;

use think\Model;

/**
 * Class LoginAccount 
 *  
 * @property int id id 
 * @property int user_id 用户ID 
 * @property string account 账号 
 * @property int account_type 账号类型 
 * @package app\sys\model 
 */
class LoginAccount extends Model {
    /**
     * 账号类型 -- 用户名
     */
    const ACCOUNT_TYPE_USERNAME = 0;

    /**
     * 账号类型 -- 手机号码
     */
    const ACCOUNT_TYPE_MOBILE = 1;

    /**
     * 账号类型 -- 邮箱
     */
    const ACCOUNT_TYPE_EMAIL = 2;

    /**
     * 默认的创建时间字段为create_time，更新时间字段为update_time
     *
     * @var string
     */
    protected $autoWriteTimestamp = 'datetime';
    
    /**
     * 别名属性
     *
     * @var array
     */
    protected $append = array('func_text');

    /**
     * 绑定登录账号
     *
     * @param int|User $user    用户ID或用户模型
     * @param string   $account 账号名称
     * @param int      $type    账号类型
     * @return boolean
     * @throws \think\Exception\DbException DB异常
     * @throws \Exception 异常
     */
    public static function bindLoginAccount($user, $account, $type = self::ACCOUNT_TYPE_USERNAME) {
        $account = trim($account);
        if (!$user) {
            throw new \Exception('用户不存在');
        }

        if (!$account) {
            throw new \Exception('账号不能为空');
        }

        if ($type != self::ACCOUNT_TYPE_USERNAME && $type != self::ACCOUNT_TYPE_MOBILE && $type != self::ACCOUNT_TYPE_EMAIL) {
            throw new \Exception('账号类型不正确');
        }

        if (is_int($user)) {
            $user = User::get($user);
            if (!$user) {
                throw new \Exception('用户不存在');
            }
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
     * @param $user
     * @param $account
     */
    public static function unbindLoginAccount($user, $account) {

    }

    public static function checkLoginAccount($account) {

    }
}