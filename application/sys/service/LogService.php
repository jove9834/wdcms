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

use app\sys\model\Log;
use app\sys\model\LogType;
use think\facade\Request;

/**
 * Class LogService
 * @package app\sys\service
 */
class LogService
{
    /**
     * 添加操作日志
     *
     * @param string $logKey      日志Key
     * @param string $description 操作描述
     * @param integer $userId     操作人ID
     * @return mixed
     */
    public static function addLog($logKey, $description = NULL, $userId = NULL) {
        try {
            $userId = $userId ? $userId : UserService::getLoginUserId();
            // 取LogType
            $logType = self::getLogTypeByName($logKey);
            if (! $logType) {
                return FALSE;
            }

            $log = new Log();
            $log->content = $description;
            $log->lt_id = $logType->id;
            $log->operator = $userId;
            $log->create_time = time();
            $log->ip_addr = Request::ip();
            $log->operate_no = Request::secureKey();

            return $log->save();
        } catch (\Exception $e) {
            \think\facade\Log::error($e->getMessage());
        }
    }

    /**
     * 取最新的操作日志
     *
     * @param integer $userId 用户ID
     * @param string  $logKey 日志KEY
     * @return null|Log
     * @throws \think\exception\DbException DB异常
     * @throws \Exception 异常
     */
    public static function getLatestLog($userId, $logKey) {
        $userId = intval($userId);
        if (!$userId) {
            return NULL;
        }

        // 取LogType
        $logType = self::getLogTypeByName($logKey);
        if (! $logType) {
            return NULL;
        }

        $list = Log::order('id', 'desc')->get(['operator' => $userId, 'lt_id' => $logType->id]);
        return $list;
    }

    /**
     * 取用户日志
     *
     * @param integer $userId 用户ID
     * @param string  $logKey 日志类型，默认为空
     * @return array|null
     * @throws \think\exception\DbException DB异常
     * @throws \Exception 异常
     */
    public static function getUserLog($userId, $logKey = NULL) {
        $userId = intval($userId);
        if (!$userId) {
            return NULL;
        }

        $where = ['operator' => $userId];
        if ($logKey) {
            $logType = self::getLogTypeByName($logKey);
            if (! $logType) {
                return NULL;
            }

            $where['lt_id'] = $logType->id;
        }

        return Log::order('id', 'desc')->all($where);
    }

    /**
     * 根据日志类型KEY取日志类型信息
     *
     * @param string $name 名称
     * @return LogType|null
     * @throws \think\exception\DbException DB异常
     * @throws \Exception 异常
     */
    public static function getLogTypeByName($name) {
        $name = trim($name);
        if (!$name) {
            throw new \Exception('日志类型Key不正确');
        }

        return LogType::get(['name' => $name]);
    }
}