<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Slince <taosikai@yeah.net>
// +----------------------------------------------------------------------
namespace app\gencode;

use app\gencode\common\Build;
use app\gencode\common\make\Config;
use app\gencode\common\make\Controller;
use app\gencode\common\make\Model;
use app\gencode\common\make\Validate;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Generate extends Command
{

    public function configure()
    {
        $this->setName('g')
            ->addOption('module', 'm', Option::VALUE_OPTIONAL,
                '所属模块名称', 'index')
            ->addOption('conn', 'c', Option::VALUE_OPTIONAL,
                '数据库连接', '')
            ->addOption('template', 't', Option::VALUE_OPTIONAL,
                '生成代码模板，默认EIP', 'default')
            ->addArgument('code', Argument::REQUIRED,'代码, all, model, controller, view, config')
            ->addArgument('table', Argument::REQUIRED,'表名')
            ->setDescription('代码生成工具');
    }

    public function execute(Input $input, Output $output)
    {
        $module = $input->getOption('module');
        $conn = $input->getOption('conn');
        $template = $input->getOption('template');
        $code = trim($input->getArgument('code'));
        $table = trim($input->getArgument('table'));

//        try {
            $context = [
                'module' => $module,
                'connection' => $conn,
                'template' => $template,
                'code' => $code,
                'table' => $table,
            ];

            if ($code === 'config') {
                // 生成配置文件
                $makeConfig = new Config($context);
                $fileName = $makeConfig->make();
            } elseif ($code === 'controller') {
                // 生成其他功能代码
                $makeController = new Controller($context);
                $fileName = $makeController->make();
            } elseif ($code === 'model') {
                // 生成其他功能代码
                $makeModel = new Model($context);
                $fileName = $makeModel->make();
            } elseif ($code === 'validate') {
                // 生成其他功能代码
                $validate = new Validate($context);
                $fileName = $validate->make();
            }

            $output->writeln(sprintf('module: %s', $module));
            $output->writeln(sprintf('table: %s', $table));
            $output->writeln(sprintf('connection: %s', $conn));
            $output->writeln(sprintf('template: %s', $template));
            $output->writeln(sprintf('code: %s', $code));
            $output->writeln(sprintf('generate file: %s', $fileName));
//        } catch (\Exception $e) {
//            exception('异常消息', 10006);
//            $output->renderException($e);
//        }
    }

}
