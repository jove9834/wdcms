<?php
/**
 * Created by PhpStorm.
 * User: huangwj
 * Date: 2016/11/7
 * Time: 下午11:30
 */
namespace app\sys\common;


/**
 * Class LoginUser
 *
 * 登录用户信息实体
 *
 * @package app\sys\common
 */
class LoginUser
{
    /**
     * 用户ID
     *
     * @var integer
     */
    protected $userId;

    /**
     * 用户名
     *
     * @var string
     */
    protected $userName;

    /**
     * 姓名
     *
     * @var string
     */
    protected $realName;

    /**
     * 性别. 0 男， 1女
     *
     * @var integer
     */
    protected $gender;

    /**
     * 头像
     *
     * @var string
     */
    protected $avatar;

    /**
     * 用户类型. 1 系统管理员， 2 普通用户
     *
     * @var integer
     */
    protected $userType;

    /**
     * 姓名首字母
     *
     * @var string
     */
    protected $firstLetter;

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * 设置用户ID
     *
     * @param int $userId 用户ID
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * @return string
     */
    public function getRealName()
    {
        return $this->realName;
    }

    /**
     * @param string $realName
     */
    public function setRealName($realName)
    {
        $this->realName = $realName;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return int
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param int $userType
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    /**
     * @return string
     */
    public function getFirstLetter()
    {
        return $this->firstLetter;
    }

    /**
     * @param string $firstLetter
     */
    public function setFirstLetter($firstLetter)
    {
        $this->firstLetter = $firstLetter;
    }

    /**
     * 取用户信息
     *
     * @return User
     * @throws \Exception
     */
    public function getUserInfo() {
        return UserModel::getInstance()->getUser($this->getUserId());
    }

}