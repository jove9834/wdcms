<?php
/**
 * Created by PhpStorm.
 * User: IGG
 * Date: 2018/9/17
 * Time: 10:29
 */

namespace app\gencode\common\make;

use app\gencode\common\Build;

abstract class BuildCode
{
    /**
     * 生成参数
     *
     * @var array
     */
    protected $context;

    /**
     * 配置信息
     *
     * @var array
     */
    protected $config;

    /**
     * Controller constructor.
     * @param $context
     */
    public function __construct($context)
    {
        $this->context = $context;
        $module = $context['module'];
        $table = $context['table'];

        $this->config = Build::getConfig($module, $table);
    }

    /**
     * 生成控制器
     *
     * @return string 生成的文件名
     * @throws \Exception
     */
    public abstract function make();
}